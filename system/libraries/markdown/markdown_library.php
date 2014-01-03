<?php
/**
 * Markdown Parser Library - A text-to-HTML conversion tool for web writers
 * based on Michel Fortin's PHP Markdown  
 * 
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2014 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

namespace InfoPotato\libraries\markdown;

class Markdown_Library {

    /**
     * How many spaces a tab character represents
     * 
     * @var integer
     */
    private $tab_width = 4;

    /**
     * Whether to keep HTML tags untouched in Markdown source
     * 
     * Set to TRUE will cause the tags being rendered by browser
     * Set to FALSE if you want to show the HTML tags verbatim in browser's output
     * 
     * @var bool
     */
    private $keep_html_tags = TRUE;
    
    /**
     * Whether to keep HTML entities untouched in Markdown source
     * 
     * HTML entities will be rendered by the browser by default.
     * Setting to FALSE will transfer HTML entities (e.g., &lt; to &amp;tl;) in source  
     * So that they can be passed verbatim (keep showing as &lt;) in browser output.
     * 
     * @var bool
     */
    private $keep_html_entities = TRUE;

    /**
     * Depth for regex to match balanced [brackets]
     * Needed to insert a maximum bracked depth while converting to PHP
     * 
     * @var integer
     */
    private $nested_brackets_depth = 6;
    
    /**
     * Depth for regex to match balanced (parenthesis)
     * Needed to insert a maximum parenthesis depth while converting to PHP
     * 
     * @var integer
     */
    private $nested_url_parenthesis_depth = 4;
    
    /**
     * Table of hash values for escaped characters:
     * 
     * @var string
     */
    private $escape_chars = '\`*_{}[]()>#+-.!';

    /**
     * URLs of the link preferences
     * 
     * @var array
     */
    private $urls = array();
    
    /**
     * Titles of the link preferences
     * 
     * @var array
     */
    private $titles = array();
    
    /**
     * Hashed HTML tags
     * 
     * @var array
     */
    private $html_hashes = array();
 
    /**
     * Status flag to avoid invalid nesting
     * 
     * @var bool
     */
    private $in_anchor = FALSE;

    /**
     * Used to track when we're inside an ordered or unordered list
     * 
     * @var integer
     */     
    private $list_level = 0;

    /**
     * Constructor
     *
     * The constructor can be passed an array of config values
     */
    public function __construct(array $config = NULL) {
        if (count($config) > 0) {
            foreach ($config as $key => $val) {
                // Using isset() requires $this->$key not to be NULL in property definition
                // property_exists() allows empty property
                if (property_exists($this, $key)) {
                    $method = 'initialize_'.$key;
                    
                    if (method_exists($this, $method)) {
                        $this->$method($val);
                    }
                } else {
                    exit("'".$key."' is not an acceptable config argument!");
                }
            }
        }
    }

    /**
     * Validate and set $tab_width
     *
     * @param $val int
     * @return void
     */
    private function initialize_tab_width($val) {
        if ( ! is_int($val)) {
            $this->invalid_argument_value('tab_width');
        }
        $this->tab_width = $val;
    }
    
    /**
     * Validate and set $keep_html_tags
     *
     * @param  $val bool
     * @return void
     */
    private function initialize_keep_html_tags($val) {
        if ( ! is_bool($val)) {
            $this->invalid_argument_value('keep_html_tags');
        }
        $this->keep_html_tags = $val;
    }
    
    /**
     * Validate and set $keep_html_entities
     *
     * @param $val bool
     * @return void
     */
    private function initialize_keep_html_entities($val) {
        if ( ! is_bool($val)) {
            $this->invalid_argument_value('keep_html_entities');
        }
        $this->keep_html_entities = $val;
    }
    
    /**
     * Validate and set $nested_brackets_depth
     *
     * @param $val int
     * @return void
     */
    private function initialize_nested_brackets_depth($val) {
        if ( ! is_int($val)) {
            $this->invalid_argument_value('nested_brackets_depth');
        }
        $this->nested_brackets_depth = $val;
    }
    
    /**
     * Validate and set $nested_url_parenthesis_depth
     *
     * @param $val int
     * @return void
     */
    private function initialize_nested_url_parenthesis_depth($val) {
        if ( ! is_int($val)) {
            $this->invalid_argument_value('nested_url_parenthesis_depth');
        }
        $this->nested_url_parenthesis_depth = $val;
    }
    
    /**
     * Validate and set $escape_chars
     *
     * @param $val string
     * @return void
     */
    private function initialize_escape_chars($val) {
        if ( ! is_string($val)) {
            $this->invalid_argument_value('escape_chars');
        }
        $this->escape_chars = $val;
    }
    
    /**
     * Output the error message for invalid argument value
     *
     * @return void
     */
    private function invalid_argument_value($arg) {
        exit('In your config array, the provided argument value of '."'".$arg."'".' is invalid.');
    }

    /**
     * Main function
     *
     * Performs some preprocessing on the input text and pass it through the document gamut
     *
     * @param string
     * @return string
     */
    public function markdown_to_html($text) {
        // Remove UTF-8 BOM and marker character in input, if present.
        $text = preg_replace('{^\xEF\xBB\xBF|\x1A}', '', $text);

        // Standardize line endings
        // DOS to Unix and Mac to Unix
        $text = preg_replace('{\r\n?}', "\n", $text);

        // Make sure $text ends with a couple of newlines
        $text .= "\n\n";

        // Convert all tabs to spaces.
        $text = $this->tab_to_spaces($text);

        // Turn block-level HTML blocks into hash entries
        $text = $this->hash_html_blocks($text);

        // Strip any lines consisting only of spaces and tabs.
        // This makes subsequent regexen easier to write, because we can
        // match consecutive blank lines with /\n+/ instead of something
        // contorted like /[ ]*\n+/ .
        $text = preg_replace('/^[ ]+$/m', '', $text);

        // Two document gamut methods, must run in the following order
        $text = $this->strip_link_definitions($text);
        $text = $this->run_basic_block_gamut($text);

        // Clear any variable which may be taking up memory unnecessarly
        $this->urls = array();
        $this->titles = array();
        $this->html_hashes = array();

        return $text."\n";
    }

    /**
     * Strips link definitions from text, stores the URLs and titles in hash references.
     *
     * @param string
     * @return string
     */
    private function strip_link_definitions($text) {
        $less_than_tab = $this->tab_width - 1;

        // Link defs are in the form: ^[id]: url "optional title"
        $text = preg_replace_callback(
            '{
            ^[ ]{0,'.$less_than_tab.'}\[(.+)\][ ]?:    # id = $1
            [ ]*
            \n?                # maybe *one* newline
            [ ]*
            (?:
            <(.+?)>            # url = $2
            |
            (\S+?)            # url = $3
            )
            [ ]*
            \n?                # maybe one newline
            [ ]*
            (?:
            (?<=\s)            # lookbehind for whitespace
            ["(]
            (.*?)            # title = $4
            [")]
            [ ]*
            )?    # title is optional
            (?:\n+|\Z)
            }xm',
            // An anonymous function as callback from PHP 5.3.0
            // But $this can be used in anonymous functions from PHP 5.4.0
            // We have to use the old-fashioned callback here
            array($this, 'strip_link_definitions_callback'),
            $text
        );
        return $text;
    }

    /**
     * Callback
     *
     * @param array
     * @return string
     */
    private function strip_link_definitions_callback($matches) {
        $link_id = strtolower($matches[1]);
        $this->urls[$link_id] = ($matches[2] === '') ? $matches[3] : $matches[2];
        $this->titles[$link_id] =& $matches[4];
        // String that will replace the block
        return ''; 
    }
    
    /**
     * Hashify HTML blocks
     * 
     * @param string
     * @return string
     */
    private function hash_html_blocks($text) {
        if ( ! $this->keep_html_tags) {
            return $text;
        }
        
        $less_than_tab = $this->tab_width - 1;

        // We only want to do this for block-level HTML tags, such as headers,
        // lists, and tables. That's because we still want to wrap <p>s around
        // "paragraphs" that are wrapped in non-block-level tags, such as anchors,
        // phrase emphasis, and spans. The list of tags we're looking for is
        // hard-coded:

        // *  List "a" is made of tags which can be both inline or block-level.
        //    These will be treated block-level when the start tag is alone on 
        //    its line, otherwise they're not matched here and will be taken as inline later.
        // *  List "b" is made of tags which are always block-level;
        $block_tags_a_re = 'ins|del';
        $block_tags_b_re = 'p|div|h[1-6]|blockquote|pre|table|dl|ol|ul|address|'.
                           'script|noscript|form|fieldset|iframe|math|svg|'.
                           'article|section|nav|aside|hgroup|header|footer|'.
                           'figure';

        // Regular expression for the content of a block tag.
        $nested_tags_level = 4;
        $attr = '
            (?>                # optional tag attributes
            \s            # starts with whitespace
            (?>
            [^>"/]+        # text outside quotes
            |
            /+(?!>)        # slash not followed by ">"
            |
            "[^"]*"        # text inside double quotes (tolerate ">")
            |
            \'[^\']*\'    # text inside single quotes (tolerate ">")
            )*
            )?    
            ';
            
        $content = str_repeat('
            (?>
            [^<]+            # content without tag
            |
            <\2            # nested opening tag
            '.$attr.'    # attributes
            (?>
            />
            |
            >', $nested_tags_level).    # end of opening tag
            '.*?'.                    # last level nested tag content
            str_repeat('
            </\2\s*>    # closing nested tag
            )
            |                
            <(?!/\2\s*>    # other tags with a different name
            )
            )*',
            $nested_tags_level);
            
        $content2 = str_replace('\2', '\3', $content);

        // First, look for nested blocks, e.g.:
        //     <div>
        //         <div>
        //         tags for inner block must be indented.
        //         </div>
        //     </div>
        //
        // The outermost tags must start at the left margin for this to match, and
        // the inner nested divs must be indented.
        // We need to do this before the next, more liberal match, because the next
        // match will start at the first `<div>` and stop at the first `</div>`.
        $text = preg_replace_callback(
            '{(?>
            (?>
                (?<=\n\n)        # Starting after a blank line
                |                # or
                \A\n?            # the beginning of the doc
            )
            (                        # save in $1

              # Match from `\n<tag>` to `</tag>\n`, handling nested tags 
              # in between.
                    
                        [ ]{0,'.$less_than_tab.'}
                        <('.$block_tags_b_re.')# start tag = $2
                        '.$attr.'>            # attributes followed by > and \n
                        '.$content.'        # content, support nesting
                        </\2>                # the matching end tag
                        [ ]*                # trailing spaces/tabs
                        (?=\n+|\Z)    # followed by a newline or end of document

            | # Special version for tags of group a.

                        [ ]{0,'.$less_than_tab.'}
                        <('.$block_tags_a_re.')# start tag = $3
                        '.$attr.'>[ ]*\n    # attributes followed by >
                        '.$content2.'        # content, support nesting
                        </\3>                # the matching end tag
                        [ ]*                # trailing spaces/tabs
                        (?=\n+|\Z)    # followed by a newline or end of document
                    
            | # Special case just for <hr />. It was easier to make a special 
              # case than to make the other regex more complicated.
            
                        [ ]{0,'.$less_than_tab.'}
                        <(hr)                # start tag = $2
                        '.$attr.'            # attributes
                        /?>                    # the matching end tag
                        [ ]*
                        (?=\n{2,}|\Z)        # followed by a blank line or end of document
            
            | # Special case for standalone HTML comments:
            
                    [ ]{0,'.$less_than_tab.'}
                    (?s:
                        <!-- .*? -->
                    )
                    [ ]*
                    (?=\n{2,}|\Z)        # followed by a blank line or end of document
            
            | # PHP and ASP-style processor instructions (<? and <%)
            
                    [ ]{0,'.$less_than_tab.'}
                    (?s:
                        <([?%])            # $2
                        .*?
                        \2>
                    )
                    [ ]*
                    (?=\n{2,}|\Z)        # followed by a blank line or end of document
                    
            )
            )}Sxmi',
            // An anonymous function as callback from PHP 5.3.0
            // But $this can be used in anonymous functions from PHP 5.4.0
            // We have to use the old-fashioned callback here
            array($this, 'hash_html_blocks_callback'),
            $text
        );

        return $text;
    }

    /**
     * Callback
     *
     * @param array
     * @return string
     */
    private function hash_html_blocks_callback($matches) {
        $text = $matches[1];
        $key = $this->hash_part($text, 'B');
        return "\n\n".$key."\n\n";
    }
    
    /**
     * Called whenever a tag must be hashed when a function insert an atomic 
     * element in the text stream. Passing $text to through this function gives
     * a unique text-token which will be reverted back when calling unhash.
     *
     * The $boundary argument specify what character should be used to surround
     * the token. By convension, 'B' is used for block elements that need not
     * to be wrapped into paragraph tags at the end, ':' is used for elements
     * that are word separators and 'X' is used in the general case.
     *
     * @param string
     * @param string
     * @return string
     */
    private function hash_part($text, $boundary = 'X') {
        // Swap back any tag hash found in $text so we don't have to `unhash` multiple times at the end
        $text = $this->unhash($text);
        
        // Then hash the block
        static $i = 0;
        // \x1A is a SUB control character, used to mark end of a file (EOF)
        // http://php.net/manual/en/regexp.reference.escape.php
        $key = $boundary."\x1A". ++$i .$boundary;
        $this->html_hashes[$key] = $text;
        // String that will replace the tag
        return $key; 
    }

    /**
     * Run block gamut tranformations.
     *
     * @param string
     * @return string
     */
    private function run_block_gamut($text) {
        // We need to escape raw HTML in Markdown source before doing anything 
        // else. This need to be done for each block, and not only at the 
        // begining in the Markdown function since hashed blocks can be part of
        // list items and could have been indented. Indented blocks would have 
        // been seen as a code block in a previous pass of hash_html_blocks.
        $text = $this->hash_html_blocks($text);
        
        return $this->run_basic_block_gamut($text);
    }
    
    /**
     * Run block gamut tranformations, without hashing HTML blocks. 
     * This is useful when HTML blocks are known to be already hashed, 
     * like in the firstwhole-document pass.
     * 
     * @param string
     * @return string
     */
    private function run_basic_block_gamut($text) {
        // These are all the transformations that form block-level
        // tags like paragraphs, headers, and list items
        // Must run in the following order
        $text = $this->do_headers($text);
        $text = $this->do_horizontal_rules($text);
        $text = $this->do_lists($text);
        $text = $this->do_code_blocks($text);
        $text = $this->do_block_quotes($text);
        
        // Finally form paragraph and restore hashed blocks
        return $this->form_paragraphs($text);
    }
    
    /**
     * Produce a horizontal rule tag (<hr />) 
     * 
     * @return void
     */
    private function do_horizontal_rules($text) {
        // You can produce a horizontal rule tag (<hr />) by placing three or more 
        // hyphens, asterisks, or underscores on a line by themselves. 
        // If you wish, you may use spaces between the hyphens or asterisks.
        return preg_replace(
            '{
            ^[ ]{0,3}    # Leading space
            ([-*_])        # $1: First marker
            (?>            # Repeated marker group
            [ ]{0,2}    # Zero, one, or two spaces.
            \1            # Marker character
            ){2,}        # Group repeated at least twice
            [ ]*        # Tailing spaces
            $            # End of line.
            }mx',
            "\n".$this->hash_part('<hr />', 'B')."\n", 
            $text
        );
    }

    /**
     * Run span gamut tranformations
     * 
     * These are all the transformations that occur *within* block-level 
     * tags like paragraphs, headers, and list items
     *
     * @param string
     * @return string
     */
    private function run_span_gamut($text) {
        // Must run in the following order
        
        // Process character escapes, code spans, and inline HTML in one shot.
        $text = $this->parse_span($text);

        // Process anchor and image tags. Images must come first,
        // because ![foo][f] looks like an anchor.
        $text = $this->do_images($text);
        $text = $this->do_anchors($text);
        
        // Make links out of things like `<http://example.com/>`
        // Must come after do_anchors, because you can use < and >
        // delimiters in inline links like [this](<url>).
        $text = $this->do_auto_links($text);
        $text = $this->encode_amps_and_angles($text);
        $text = $this->do_italics_and_bold($text);
        $text = $this->do_line_breaks($text);

        return $text;
    }

    /**
     * Do hard breaks
     * 
     * @param string
     * @return string
     */
    private function do_line_breaks($text) {
        return preg_replace_callback(
            '/ {2,}\n/', 
            // An anonymous function as callback from PHP 5.3.0
            // But $this can be used in anonymous functions from PHP 5.4.0
            // We have to use the old-fashioned callback here
            array($this, 'do_line_breaks_callback'),
            $text
        );
    }
    
    /**
     * Callback
     *
     * @param array
     * @return string
     */
    private function do_line_breaks_callback($matches) {
        // Add a newline "\n" right after the line break tag 
        return $this->hash_part("<br />\n");
    }
    
    /**
     * Turn Markdown link shortcuts into XHTML <a> tags
     *
     * @return void
     */
    private function do_anchors($text) {
        // do_anchors() is called recurively when processing the textual
        // content of the link. And run_span_gaumt() is called in the two 
        // callbacks below, which, in turn, will call do_anchors() again.
        // So we have to use this flag.
        if ($this->in_anchor) {
            return $text;
        }
        
        $this->in_anchor = TRUE;

        // These two variables are the same as those two in do_images()
        $nested_brackets_regex = str_repeat('(?>[^\[\]]+|\[', $this->nested_brackets_depth).str_repeat('\])*', $this->nested_brackets_depth);
        $nested_url_parenthesis_regex = str_repeat('(?>[^()\s]+|\(', $this->nested_url_parenthesis_depth).str_repeat('(?>\)))*', $this->nested_url_parenthesis_depth);   

        // Markdown supports two style of links: inline and reference.
        
        // First, handle reference-style links: [link text] [id]
        // This is [an example][id] reference-style link.
        // You can optionally use a space to separate the sets of brackets:
        // This is [an example] [id] reference-style link.
        // [id]: http://example.com/  "Optional Title Here"
        $text = preg_replace_callback(
            '{
            (                    # wrap whole match in $1
            \[
            ('.$nested_brackets_regex.')    # link text = $2
            \]

            [ ]?                # one optional space
            (?:\n[ ]*)?        # one optional newline followed by spaces

            \[
            (.*?)        # id = $3
            \]
            )
            }xs',
            // An anonymous function as callback from PHP 5.3.0
            // But $this can be used in anonymous functions from PHP 5.4.0
            // We have to use the old-fashioned callback here
            array($this, 'do_anchors_reference_callback'),
            $text
        );

        // Next, inline-style links: [link text](url "optional title")
        // This is [an example](http://example.com/ "Title") inline link.
        // [This link](http://example.net/) has no title attribute.
        $text = preg_replace_callback(
            '{
            (                # wrap whole match in $1
            \[
            ('.$nested_brackets_regex.')    # link text = $2
            \]
            \(            # literal paren
            [ \n]*
            (?:
            <(.+?)>    # href = $3
            |
            ('.$nested_url_parenthesis_regex.')    # href = $4
            )
            [ \n]*
            (            # $5
            ([\'"])    # quote char = $6
            (.*?)        # Title = $7
            \6        # matching quote
            [ \n]*    # ignore any spaces/tabs between closing quote and )
            )?            # title is optional
            \)
            )
            }xs',
            // An anonymous function as callback from PHP 5.3.0
            // But $this can be used in anonymous functions from PHP 5.4.0
            // We have to use the old-fashioned callback here
            array($this, 'do_anchors_inline_callback'),
            $text
        );
        
        // Last, handle reference-style shortcuts: [link text]
        // These must come last in case you've also got [link text][1]
        // or [link text](/foo)
        $text = preg_replace_callback(
            '{
            (                    # wrap whole match in $1
            \[
            ([^\[\]]+)        # link text = $2; can\'t contain [ or ]
            \]
            )
            }xs',
            // An anonymous function as callback from PHP 5.3.0
            // But $this can be used in anonymous functions from PHP 5.4.0
            // We have to use the old-fashioned callback here
            array($this, 'do_anchors_reference_callback'),
            $text
        );

        $this->in_anchor = FALSE;
        
        return $text;
    }

    /**
     * Callback
     *
     * @param array
     * @return string
     */
    private function do_anchors_reference_callback($matches) {
        $whole_match = $matches[1];
        $link_text = $matches[2];
        $link_id =& $matches[3]; // The title is optional

        if ($link_id === '') {
            // For shortcut links like [this][] or [this].
            $link_id = $link_text;
        }
        
        // lower-case and turn embedded newlines into spaces
        $link_id = strtolower($link_id);
        $link_id = preg_replace('{[ ]?\n}', ' ', $link_id);

        if (isset($this->urls[$link_id])) {
            $url = $this->urls[$link_id];
            // We've got to encode these to avoid conflicting with italics/bold
            $url = $this->encode_attribute($url);
            
            $result = '<a href="'.$url.'"';
            if (isset($this->titles[$link_id])) {
                $title = $this->titles[$link_id];
                $title = $this->encode_attribute($title);
                $result .= ' title="'.$title.'"';
            }
        
            $link_text = $this->run_span_gamut($link_text);
            $result .= '>'.$link_text.'</a>';
            $result = $this->hash_part($result);
        } else {
            $result = $whole_match;
        }
        
        return $result;
    }
    
    /**
     * Callback
     *
     * @param array
     * @return string
     */
    private function do_anchors_inline_callback($matches) {
        $whole_match = $matches[1];
        $link_text = $this->run_span_gamut($matches[2]);
        $url = ($matches[3] === '') ? $matches[4] : $matches[3];
        $title =& $matches[7];

        // We've got to encode these to avoid conflicting with italics/bold
        $url = $this->encode_attribute($url);

        $result = '<a href="'.$url.'"';
        if (isset($title)) {
            $title = $this->encode_attribute($title);
            $result .= ' title="'.$title.'"';
        }
        
        $link_text = $this->run_span_gamut($link_text);
        $result .= '>'.$link_text.'</a>';

        return $this->hash_part($result);
    }
    
    /**
     * Turn Markdown image shortcuts into <img> tags.
     *
     * @param string
     * @return string
     */
    private function do_images($text) {
        // These two variables are the same as those two in do_anchors()
        $nested_brackets_regex = str_repeat('(?>[^\[\]]+|\[', $this->nested_brackets_depth).str_repeat('\])*', $this->nested_brackets_depth);
        $nested_url_parenthesis_regex = str_repeat('(?>[^()\s]+|\(', $this->nested_url_parenthesis_depth).str_repeat('(?>\)))*', $this->nested_url_parenthesis_depth);   
        
        // First, handle reference-style labeled images: ![alt text][id]
        $text = preg_replace_callback(
            '{
            (                # wrap whole match in $1
            !\[
            ('.$nested_brackets_regex.')        # alt text = $2
            \]

            [ ]?                # one optional space
            (?:\n[ ]*)?        # one optional newline followed by spaces

            \[
            (.*?)        # id = $3
            \]

            )
            }xs', 
            // An anonymous function as callback from PHP 5.3.0
            // But $this can be used in anonymous functions from PHP 5.4.0
            // We have to use the old-fashioned callback here
            array($this, 'do_images_reference_callback'), 
            $text
        );

        // Next, handle inline images:  ![alt text](url "optional title")
        // Don't forget: encode * and _
        $text = preg_replace_callback(
            '{
            (                # wrap whole match in $1
            !\[
            ('.$nested_brackets_regex.')        # alt text = $2
            \]
            \s?            # One optional whitespace character
            \(            # literal paren
            [ \n]*
            (?:
            <(\S*)>    # src url = $3
            |
            ('.$nested_url_parenthesis_regex.')    # src url = $4
            )
            [ \n]*
            (            # $5
            ([\'"])    # quote char = $6
            (.*?)        # title = $7
            \6        # matching quote
            [ \n]*
            )?            # title is optional
            \)
            )
            }xs',
            // An anonymous function as callback from PHP 5.3.0
            // But $this can be used in anonymous functions from PHP 5.4.0
            // We have to use the old-fashioned callback here
            array($this, 'do_images_inline_callback'), 
            $text
        );

        return $text;
    }

    /**
     * Callback
     *
     * @param array
     * @return string
     */
    private function do_images_reference_callback($matches) {
        $whole_match = $matches[1];
        $alt_text = $matches[2];
        $link_id = strtolower($matches[3]);

        if ($link_id === '') {
            // For shortcut links like ![this][].
            $link_id = strtolower($alt_text); 
        }

        $alt_text = $this->encode_attribute($alt_text);
        if (isset($this->urls[$link_id])) {
            // We've got to encode these to avoid conflicting with italics/bold
            $url = $this->encode_attribute($this->urls[$link_id]);
            $result = '<img src="'.$url.'" alt="'.$alt_text.'"';
            if (isset($this->titles[$link_id])) {
                $title = $this->titles[$link_id];
                $title = $this->encode_attribute($title);
                $result .=  ' title="'.$title.'"';
            }
            $result .= ' />'; // Closing tag suffix for <img />
            $result = $this->hash_part($result);
        } else {
            // If there's no such link ID, leave intact
            $result = $whole_match;
        }

        return $result;
    }
    
    /**
     * Callback
     *
     * @param array
     * @return string
     */
    private function do_images_inline_callback($matches) {
        $whole_match = $matches[1];
        $alt_text = $matches[2];
        $url = ($matches[3] === '') ? $matches[4] : $matches[3];
        $title =& $matches[7];

        $alt_text = $this->encode_attribute($alt_text);
        // We've got to encode these to avoid conflicting with italics/bold
        $url = $this->encode_attribute($url);
        $result = '<img src="'.$url.'" alt="'.$alt_text.'"';
        
        if (isset($title)) {
            $title = $this->encode_attribute($title);
            $result .= ' title="'.$title.'"'; // $title already quoted
        }
        $result .= ' />'; // Closing tag suffix for <img />

        return $this->hash_part($result);
    }
    
    /**
     * Markdown supports two styles of headers: Setext and atx
     *
     * @param string
     * @return string
     */
    private function do_headers($text) {
        // Setext-style headers:
        // Header 1
        // ========
        //  
        // Header 2
        // --------
        $text = preg_replace_callback(
            '{ ^(.+?)[ ]*\n(=+|-+)[ ]*\n+ }mx', 
            // An anonymous function as callback from PHP 5.3.0
            // But $this can be used in anonymous functions from PHP 5.4.0
            // We have to use the old-fashioned callback here
            array($this, 'do_headers_setext_callback'), 
            $text
        );

        // atx-style headers:
        // # Header 1
        // ## Header 2
        // ## Header 2 with closing hashes ##
        // ...
        // ###### Header 6
        $text = preg_replace_callback(
            '{
            ^(\#{1,6})    # $1 = string of #\'s
            [ ]*
            (.+?)         # $2 = Header text
            [ ]*
            \#*           # optional closing #\'s (not counted)
            \n+
            }xm',
            // An anonymous function as callback from PHP 5.3.0
            // But $this can be used in anonymous functions from PHP 5.4.0
            // We have to use the old-fashioned callback here
            array($this, 'do_headers_atx_callback'), 
            $text
        );

        return $text;
    }
    
    /**
     * Callback
     *
     * @param array
     * @return string
     */
    private function do_headers_setext_callback($matches) {
        // Terrible hack to check we haven't found an empty list item.
        if (($matches[2] === '-') && preg_match('{^-(?: |$)}', $matches[1])) {
            return $matches[0];
        }
        
        $level = ($matches[2]{0} === '=') ? 1 : 2;
        $block = '<h'.$level.'>'.$this->run_span_gamut($matches[1]).'</h'.$level.'>';
        return "\n" . $this->hash_part($block, 'B') . "\n\n";
    }

    /**
     * Callback
     *
     * @param array
     * @return string
     */
    private function do_headers_atx_callback($matches) {
        $level = strlen($matches[1]);
        $block = '<h'.$level.'>'.$this->run_span_gamut($matches[2]).'</h'.$level.'>';
        return "\n".$this->hash_part($block, 'B')."\n\n";
    }
    
    /**
     * Form HTML ordered (numbered) and unordered (bulleted) lists.
     *
     * @param string
     * @return string
     */
    private function do_lists($text) {
        $less_than_tab = $this->tab_width - 1;

        // Re-usable patterns to match list item bullets and number markers
        $marker_ul_regex = '[*+-]';
        $marker_ol_regex = '\d+[\.]';

        $markers_relist = array(
            $marker_ul_regex => $marker_ol_regex,
            $marker_ol_regex => $marker_ul_regex,
        );

        foreach ($markers_relist as $marker_regex => $other_marker_regex) {
            // Re-usable pattern to match any entirel ul or ol list
            $whole_list_re = '
                (                                # $1 = whole list
                (                                # $2
                ([ ]{0,'.$less_than_tab.'})    # $3 = number of spaces
                ('.$marker_regex.')            # $4 = first list item marker
                [ ]+
                )
                (?s:.+?)
                (                                # $5
                \z
                |
                \n{2,}
                (?=\S)
                (?!                        # Negative lookahead for another list item marker
                [ ]*
                '.$marker_regex.'[ ]+
                )
                |
                (?=                        # Lookahead for another kind of list
                \n
                \3                        # Must have the same indentation
                '.$other_marker_regex.'[ ]+
                )
                )
                )
                '; // mx
            
            // We use a different prefix before nested lists than top-level lists.
            // See extended comment in process_list_items().

            if ($this->list_level) {
                $text = preg_replace_callback('{
                    ^
                    '.$whole_list_re.'
                    }mx', 
                    // An anonymous function as callback from PHP 5.3.0
                    // But $this can be used in anonymous functions from PHP 5.4.0
                    // We have to use the old-fashioned callback here
                    array($this, 'do_lists_callback'), 
                    $text
                );
            } else {
                $text = preg_replace_callback(
                    '{
                    (?:(?<=\n)\n|\A\n?)  # Must eat the newline
                    '.$whole_list_re.'
                    }mx', 
                    // An anonymous function as callback from PHP 5.3.0
                    // But $this can be used in anonymous functions from PHP 5.4.0
                    // We have to use the old-fashioned callback here
                    array($this, 'do_lists_callback'), 
                    $text
                );
            }
        }

        return $text;
    }
    
    /**
     * Callback
     *
     * @param array
     * @return string
     */
    private function do_lists_callback($matches) {
        // Re-usable patterns to match list item bullets and number markers
        $marker_ul_regex = '[*+-]';
        $marker_ol_regex = '\d+[\.]';

        $list = $matches[1];
        $list_type = preg_match("/$marker_ul_regex/", $matches[4]) ? 'ul' : 'ol';
        $marker_any_regex = ($list_type === 'ul') ? $marker_ul_regex : $marker_ol_regex;
        
        $list .= "\n";
        $result = $this->process_list_items($list, $marker_any_regex);
        $result = $this->hash_part('<'.$list_type.">\n".$result.'</'.$list_type.'>', 'B');
        
        return "\n".$result."\n\n";
    }

    /**
     * Process the contents of a single ordered or unordered list, splitting it
     * into individual list items.
     * 
     * @return void
     */
    private function process_list_items($list_str, $marker_any_regex) {
        // The $this->list_level global keeps track of when we're inside a list.
        // Each time we enter a list, we increment it; when we leave a list,
        // we decrement. If it's zero, we're not in a list anymore.
        //
        // We do this because when we're not inside a list, we want to treat
        // something like this:
        //
        //        I recommend upgrading to version
        //        8. Oops, now this line is treated
        //        as a sub-list.
        //
        // As a single paragraph, despite the fact that the second line starts
        // with a digit-period-space sequence.
        //
        // Whereas when we're inside a list (or sub-list), that line will be
        // treated as the start of a sub-list. What a kludge, huh? This is
        // an aspect of Markdown's syntax that's hard to parse perfectly
        // without resorting to mind-reading. Perhaps the solution is to
        // change the syntax rules such that sub-lists must start with a
        // starting cardinal number; e.g. "1." or "a.".
        
        $this->list_level++;

        // Trim trailing blank lines:
        $list_str = preg_replace("/\n{2,}\\z/", "\n", $list_str);

        $list_str = preg_replace_callback(
            '{
            (\n)?                            # leading line = $1
            (^[ ]*)                          # leading whitespace = $2
            ('.$marker_any_regex.'              # list marker and space = $3
            (?:[ ]+|(?=\n))                  # space only required if item is not empty
            )
            ((?s:.*?))                       # list item text   = $4
            (?:(\n+(?=\n))|\n)               # tailing blank line = $5
            (?= \n* (\z | \2 ('.$marker_any_regex.') (?:[ ]+|(?=\n))))
            }xm',
            // An anonymous function as callback from PHP 5.3.0
            // But $this can be used in anonymous functions from PHP 5.4.0
            // We have to use the old-fashioned callback here
            array($this, 'process_list_items_callback'), 
            $list_str
        );

        $this->list_level--;
        
        return $list_str;
    }

    /**
     * Callback
     *
     * @param array
     * @return string
     */
    private function process_list_items_callback($matches) {
        $leading_line =& $matches[1];
        $leading_space =& $matches[2];
        $marker_space = $matches[3];
        $item = $matches[4];
        $tailing_blank_line =& $matches[5];

        if ($leading_line || $tailing_blank_line || preg_match('/\n{2,}/', $item)) {
            // Replace marker with the appropriate whitespace indentation
            $item = $leading_space . str_repeat(' ', strlen($marker_space)).$item;
            $item = $this->run_block_gamut($this->outdent($item)."\n");
        } else {
            // Recursion for sub-lists:
            $item = $this->do_lists($this->outdent($item));
            $item = preg_replace('/\n+$/', '', $item);
            $item = $this->run_span_gamut($item);
        }

        return '<li>'.$item."</li>\n";
    }
    
    /**
     * Process Markdown `<pre><code>` blocks.
     *
     * @param string
     * @return string
     */
    private function do_code_blocks($text) {
        $text = preg_replace_callback(
            '{
            (?:\n\n|\A\n?)
            (                # $1 = the code block -- one or more lines, starting with a space/tab
            (?>
            [ ]{'.$this->tab_width.'}  # Lines must start with a tab or a tab-width of spaces
            .*\n+
            )+
            )
            ((?=^[ ]{0,'.$this->tab_width.'}\S)|\Z)    # Lookahead for non-space at line-start, or end of doc
            }xm',
            // An anonymous function as callback from PHP 5.3.0
            // But $this can be used in anonymous functions from PHP 5.4.0
            // We have to use the old-fashioned callback here
            array($this, 'do_code_blocks_callback'), 
            $text
        );

        return $text;
    }

    /**
     * Callback
     *
     * @param array
     * @return string
     */
    private function do_code_blocks_callback($matches) {
        $codeblock = $this->outdent($matches[1]);
        $codeblock = htmlspecialchars($codeblock, ENT_NOQUOTES);
        // Trim leading newlines and trailing newlines
        $codeblock = preg_replace('/\A\n+|\n+\z/', '', $codeblock);
        $codeblock = '<pre><code>'.$codeblock."\n</code></pre>";
        
        return "\n\n".$this->hash_part($codeblock, 'B')."\n\n";
    }
    
    /**
     * Create a code span markup for $code. Called from handle_span_token.
     *
     * @param string
     * @return string
     */
    private function make_code_span($code) {
        $code = htmlspecialchars(trim($code), ENT_NOQUOTES);
        
        return $this->hash_part('<code>'.$code.'</code>');
    }

    /**
     * Italics and Bold
     *
     * @param string
     * @return string
     */
    private function do_italics_and_bold($text) {
        $em_strong_prepared_regex_list = array();
              
        $em_regex_list = array(
            '' => '(?:(?<!\*)\*(?!\*)|(?<!_)_(?!_))(?=\S|$)(?![\.,:;]\s)',
            '*' => '(?<=\S|^)(?<!\*)\*(?!\*)',
            '_' => '(?<=\S|^)(?<!_)_(?!_)',
        );
        
        $strong_regex_list = array(
            '' => '(?:(?<!\*)\*\*(?!\*)|(?<!_)__(?!_))(?=\S|$)(?![\.,:;]\s)',
            '**' => '(?<=\S|^)(?<!\*)\*\*(?!\*)',
            '__' => '(?<=\S|^)(?<!_)__(?!_)',
        );

        $em_strong_regex_list = array(
            '' => '(?:(?<!\*)\*\*\*(?!\*)|(?<!_)___(?!_))(?=\S|$)(?![\.,:;]\s)',
            '***' => '(?<=\S|^)(?<!\*)\*\*\*(?!\*)',
            '___' => '(?<=\S|^)(?<!_)___(?!_)',
        );
        
        // Prepare regular expressions for searching emphasis tokens in any context.
        foreach ($em_regex_list as $em => $em_regex) {
            foreach ($strong_regex_list as $strong => $strong_regex) {
                // Construct list of allowed token expressions
                $token_regex_list = array();
                if (isset($em_strong_regex_list["$em$strong"])) {
                    $token_regex_list[] = $em_strong_regex_list["$em$strong"];
                }
                $token_regex_list[] = $em_regex;
                $token_regex_list[] = $strong_regex;
                
                // Construct master expression from list
                $em_strong_prepared_regex_list["$em$strong"] = '{('. implode('|', $token_regex_list) .')}';
            }
        }
        // Done preparation

        $token_stack = array(''); // The first array element is empty
        $text_stack = array('');  // The first array element is empty
        $em = '';
        $strong = '';
        $tree_char_em = FALSE;
        
        while (1) {
            // Get prepared regular expression for seraching emphasis tokens in current context
            $token_regex = $em_strong_prepared_regex_list["$em$strong"];
            
            // Each loop iteration searches for the next emphasis token
            // Each token is then passed to handle_span_token
            $parts = preg_split($token_regex, $text, 2, PREG_SPLIT_DELIM_CAPTURE);
            $text_stack[0] .= $parts[0];
            $token =& $parts[1];
            $text =& $parts[2];
            
            if (empty($token)) {
                // Reached end of text span: empty stack without emitting.
                // any more emphasis.
                while ($token_stack[0]) {
                    $text_stack[1] .= array_shift($token_stack);
                    $text_stack[0] .= array_shift($text_stack);
                }
                break;
            }
            
            $token_len = strlen($token);
            if ($tree_char_em) {
                // Reached closing marker while inside a three-char emphasis.
                if ($token_len === 3) {
                    // Three-char closing marker, close em and strong.
                    array_shift($token_stack);
                    $span = array_shift($text_stack);
                    $span = $this->run_span_gamut($span);
                    $span = '<strong><em>'.$span.'</em></strong>';
                    $text_stack[0] .= $this->hash_part($span);
                    $em = '';
                    $strong = '';
                } else {
                    // Other closing marker: close one em or strong and
                    // change current token state to match the other
                    // E.g., $token = 'abc'; Then $token{0} will be 'a', $token{1} 'b', and $token{2} 'c'
                    $token_stack[0] = str_repeat($token{0}, 3 - $token_len);
                    $tag = ($token_len === 2) ? 'strong' : 'em';
                    $span = $text_stack[0];
                    $span = $this->run_span_gamut($span);
                    $span = '<'.$tag.'>'.$span.'</'.$tag.'>';
                    $text_stack[0] = $this->hash_part($span);
                    $$tag = ''; // $$tag stands for $em or $strong
                }
                $tree_char_em = FALSE;
            } elseif ($token_len === 3) {
                if ($em) {
                    // Reached closing marker for both em and strong.
                    // Closing strong marker
                    for ($i = 0; $i < 2; ++$i) {
                        $shifted_token = array_shift($token_stack);
                        $tag = (strlen($shifted_token) === 2) ? 'strong' : 'em';
                        $span = array_shift($text_stack);
                        $span = $this->run_span_gamut($span);
                        $span = '<'.$tag.'>'.$span.'</'.$tag.'>';
                        $text_stack[0] .= $this->hash_part($span);
                        $$tag = ''; // $$tag stands for $em or $strong
                    }
                } else {
                    // Reached opening three-char emphasis marker. Push on token stack; 
                    // will be handled by the special condition above.
                    // E.g., $token = 'abc'; Then $token{0} will be 'a', $token{1} 'b', and $token{2} 'c'
                    $em = $token{0};
                    $strong = "$em$em";
                    array_unshift($token_stack, $token);
                    array_unshift($text_stack, '');
                    $tree_char_em = TRUE;
                }
            } elseif ($token_len === 2) {
                if ($strong) {
                    // Unwind any dangling emphasis marker
                    if (strlen($token_stack[0]) === 1) {
                        $text_stack[1] .= array_shift($token_stack);
                        $text_stack[0] .= array_shift($text_stack);
                    }
                    // Closing strong marker
                    array_shift($token_stack);
                    $span = array_shift($text_stack);
                    $span = $this->run_span_gamut($span);
                    $span = '<strong>'.$span.'</strong>';
                    $text_stack[0] .= $this->hash_part($span);
                    $strong = '';
                } else {
                    array_unshift($token_stack, $token);
                    array_unshift($text_stack, '');
                    $strong = $token;
                }
            } else {
                // Here $token_len === 1
                if ($em) {
                    if (strlen($token_stack[0]) === 1) {
                        // Closing emphasis marker
                        array_shift($token_stack);
                        $span = array_shift($text_stack);
                        $span = $this->run_span_gamut($span);
                        $span = '<em>'.$span.'</em>';
                        $text_stack[0] .= $this->hash_part($span);
                        $em = '';
                    } else {
                        $text_stack[0] .= $token;
                    }
                } else {
                    array_unshift($token_stack, $token);
                    array_unshift($text_stack, '');
                    $em = $token;
                }
            }
        }
        
        return $text_stack[0];
    }

    /**
     * Block quotes
     *
     * @param string
     * @return string
     */
    private function do_block_quotes($text) {
        $text = preg_replace_callback(
            '/
            (                    # Wrap whole match in $1
            (?>
            ^[ ]*>[ ]?           # ">" at the start of a line
            .+\n                 # rest of the first line
            (.+\n)*              # subsequent consecutive lines
            \n*                  # blanks
            )+
            )
            /xm',
            // An anonymous function as callback from PHP 5.3.0
            // But $this can be used in anonymous functions from PHP 5.4.0
            // We have to use the old-fashioned callback here
            array($this, 'do_block_quotes_callback'), 
            $text
        );

        return $text;
    }
    
    /**
     * Callback
     *
     * @param array
     * @return string
     */
    private function do_block_quotes_callback($matches) {
        $bq = $matches[1];
        // Trim one level of quoting - trim whitespace-only lines
        $bq = preg_replace('/^[ ]*>[ ]?|^[ ]+$/m', '', $bq);
        $bq = $this->run_block_gamut($bq);  // recurse

        $bq = preg_replace('/^/m', "  ", $bq);
        // These leading spaces cause problem with <pre> content, so we need to fix that:
        $bq = preg_replace_callback(
            '{(\s*<pre>.+?</pre>)}sx', 
            // An anonymous function as callback from PHP 5.3.0
            // But $this can be used in anonymous functions from PHP 5.4.0
            function ($matches) {
                return preg_replace('/^  /m', '', $matches[1]);
            }, 
            $bq
        );

        return "\n". $this->hash_part("<blockquote>\n".$bq."\n</blockquote>", 'B')."\n\n";
    }
    
    /**
     * Form paragraphs
     *
     * @param string to process with html <p> tags
     * @return string
     */
    private function form_paragraphs($text) {
        // Strip leading and trailing lines
        $text = preg_replace('/\A\n+|\n+\z/', '', $text);

        $grafs = preg_split('/\n{2,}/', $text, -1, PREG_SPLIT_NO_EMPTY);

        // Wrap <p> tags and unhashify HTML blocks
        foreach ($grafs as $key => $value) {
            if ( ! preg_match('/^B\x1A[0-9]+B$/', $value)) {
                // Is a paragraph
                $value = $this->run_span_gamut($value);
                $value = preg_replace('/^([ ]*)/', '<p>', $value);
                $value .= '</p>';
                $grafs[$key] = $this->unhash($value);
            } else {
                // Is a block
                // Modify elements of @grafs in-place...
                $graf = $value;
                $block = $this->html_hashes[$graf];
                $graf = $block;
                $grafs[$key] = $graf;
            }
        }

        return implode("\n\n", $grafs);
    }

    /**
     * Encode text for a double-quoted HTML attribute. This function
     * is *not* suitable for attributes enclosed in single quotes.
     *
     * @param string
     * @return string
     */
    private function encode_attribute($text) {
        $text = $this->encode_amps_and_angles($text);
        return str_replace('"', '&quot;', $text);
    }
    
    /**
     * Smart processing for ampersands and angle brackets that need to 
     * be encoded. Valid character entities are left alone unless the
     * no-entities mode is set.
     *
     * @return void
     */
    private function encode_amps_and_angles($text) {
        if ($this->keep_html_entities) {
            // Ampersand-encoding based entirely on Nat Irons's Amputator
            // MT plugin: <http://bumppo.net/projects/amputator/>
            $text = preg_replace('/&(?!#?[xX]?(?:[0-9a-fA-F]+|\w+);)/', '&amp;', $text);
        } else {
            $text = str_replace('&', '&amp;', $text);
        }
        // Encode remaining <'s
        return str_replace('<', '&lt;', $text);
    }

    /**
     * Markdown supports a shortcut style for creating "automatic" links for 
     * URLs and email addresses: simply surround the URL or email address with angle brackets
     * Show the actual text of a URL or email address, and also have it clickable
     * 
     *
     * @param string
     * @return string
     */
    private function do_auto_links($text) {
        $text = preg_replace_callback(
            '{<((https?|ftp|dict):[^\'">\s]+)>}i', 
            // An anonymous function as callback from PHP 5.3.0
            // But $this can be used in anonymous functions from PHP 5.4.0
            // We have to use the old-fashioned callback here
            array($this, 'do_auto_links_url_callback'),
            $text
        );

        // Email addresses: <address@domain.foo>
        $text = preg_replace_callback(
            '{
            <
            (?:mailto:)?
            (
            (?:
            [-!#$%&\'*+/=?^_`.{|}~\w\x80-\xFF]+
            |
            ".*?"
            )
            \@
            (?:
            [-a-z0-9\x80-\xFF]+(\.[-a-z0-9\x80-\xFF]+)*\.[a-z]+
            |
            \[[\d.a-fA-F:]+\]    # IPv4 & IPv6
            )
            )
            >
            }xi',
            // An anonymous function as callback from PHP 5.3.0
            // But $this can be used in anonymous functions from PHP 5.4.0
            // We have to use the old-fashioned callback here
            array($this, 'do_auto_links_email_callback'),
            $text
        );

        return $text;
    }

    /**
     * Callback
     *
     * @param array
     * @return string
     */
    private function do_auto_links_url_callback($matches) {
        $url = $this->encode_attribute($matches[1]);
        $link = '<a href="'.$url.'">'.$url.'</a>';
        
        return $this->hash_part($link);
    }
    
    /**
     * Callback
     *
     * @param array
     * @return string
     */
    private function do_auto_links_email_callback($matches) { 
        // Encode an email address to a mailto link 
        // with each character of the address encoded as either a decimal or hex entity
        // Based on a filter by Matthew Wickline, posted to BBEdit-Talk.
        // With some optimizations by Milian Wolff.
        $addr = 'mailto:'.$matches[1];
        $chars = preg_split('/(?<!^)(?!$)/', $addr);
        $seed = (int) abs(crc32($addr) / strlen($addr)); // Deterministic seed.
        
        foreach ($chars as $key => $char) {
            $ord = ord($char);
            // Ignore non-ascii chars
            if ($ord < 128) {
                $r = ($seed * (1 + $key)) % 100; // Pseudo-random function.
                // Roughly 10% raw, 45% hex, 45% dec
                // '@' *must* be encoded. I insist
                if ($r <= 90 || $char === '@') {
                    if ($r < 45) {
                        $chars[$key] = '&#x'.dechex($ord).';';
                    } else {
                        $chars[$key] = '&#'.$ord.';';
                    }
                } 
            }
        }
        
        $addr = implode('', $chars);
        $text = implode('', array_slice($chars, 7)); // Text without `mailto:`
        
        $link = '<a href="'.$addr.'">'.$text.'</a>';
        
        return $this->hash_part($link);
    }

    /**
     * Parse the string into tokens, hashing embeded HTML,
     * escaped characters and handling code spans.
     * 
     * @param string
     * @return string
     */
    private function parse_span($str) {
        $escape_chars_regex = '['.preg_quote($this->escape_chars).']';
        
        $output = '';
        
        $span_regex = '{
            (
            \\\\'.$escape_chars_regex.'
            |
            (?<![`\\\\])
            `+                         # code span marker
            '.( ! $this->keep_html_tags ? '' : '
                |
                <!--    .*?     -->        # comment
                |
                <\?.*?\?> | <%.*?%>        # processing instruction
                |
                <[!$]?[-a-zA-Z0-9:_]+      # regular tags
                (?>
                \s
                (?>[^"\'>]+|"[^"]*"|\'[^\']*\')*
                )?
                >
                |
                <[-a-zA-Z0-9:_]+\s*/>     # xml-style empty tag
                |
                </[-a-zA-Z0-9:_]+\s*>     # closing tag
            ').'
            )
            }xs';

        while (1) {
            // Each loop iteration seach for either the next tag, the next 
            // openning code span marker, or the next escaped character. 
            // Each token is then passed to handle_span_token.
            $parts = preg_split($span_regex, $str, 2, PREG_SPLIT_DELIM_CAPTURE);
            
            // Create token from text preceding tag.
            if ($parts[0] !== '') {
                $output .= $parts[0];
            }
            
            // Check if we reach the end.
            if (isset($parts[1])) {
                $span = $this->handle_span_token($parts[1], $parts[2]);   
                $output .= $span['token'];
                $str = $span['str']; // $str may be updated by handle_span_token()
            } else {
                break;
            }
        }

        return $output;
    }
    
    /**
     * Handle $token provided by parse_span by determining its nature and 
     * returning the corresponding value that should replace it.
     * 
     * @param string
     * @param string
     * @return array
     */
    private function handle_span_token($token, $str) {
        $span = array(
            'token' => '',
            'str' => $str
        );

        // E.g., $token = 'abc'; Then $token{0} will be 'a', $token{1} 'b', and $token{2} 'c'
        if ($token{0} === '\\') {
            // ord() returns the ASCII value of the first character of $token{1}
            $span['token'] = $this->hash_part('&#'. ord($token{1}). ';'); // No change to $span['str']
        } elseif ($token{0} === '`') {
            // Search for end marker in remaining text
            if (preg_match('/^(.*?[^`])'.preg_quote($token).'(?!`)(.*)$/sm', $str, $matches)) {
                $codespan = $this->make_code_span($matches[1]);
                $span['token'] = $this->hash_part($codespan);
                $span['str'] = $matches[2]; // Update $span['str']
            } else {
                // Return as text since no ending marker found
                $span['token'] = $token; // No change to $span['str']
            }
        } else {
            $span['token'] = $this->hash_part($token); // No change to $span['str']
        }

        return $span;
    }

    /**
     * Remove one level of line-leading tabs or spaces
     *
     * @param    string
     * @return    string
     */
    private function outdent($text) {
        return preg_replace('/^(\t|[ ]{1,'.$this->tab_width.'})/m', '', $text);
    }

    /**
     * Replace tabs with the appropriate amount of spaces.
     * 
     * @param string
     * @return string
     */
    private function tab_to_spaces($text) {
        // For each line we separate the line in blocks delemited by
        // tab characters. Then we reconstruct every line by adding the 
        // appropriate number of space between each blocks.
        $text = preg_replace_callback(
            '/^.*\t.*$/m', 
            // An anonymous function as callback from PHP 5.3.0
            // But $this can be used in anonymous functions from PHP 5.4.0
            // We have to use the old-fashioned callback here
            array($this, 'tab_to_spaces_callback'),
            $text
        );

        return $text;
    }

    /**
     * Callback
     *
     * @param array
     * @return string
     */
    private function tab_to_spaces_callback($matches) {
        $line = $matches[0];

        // Split in blocks.
        $blocks = explode("\t", $line);
        // Add each blocks to the line.
        $line = $blocks[0];
        unset($blocks[0]); // Do not add first block twice.
        foreach ($blocks as $block) {
            // Calculate amount of space, insert spaces, insert block.
            $amount = $this->tab_width - ($this->utf8_strlen($line) % $this->tab_width);
            $line .= str_repeat(' ', $amount) . $block;
        }
        return $line;
    }
    
    /**
     * Unicode aware replacement for *strlen*.
     *
     * Returns the number of characters in the string (not the number of bytes),
     * replacing multibyte characters with a single byte equivalent utf8_decode
     * converts characters that are not in ISO-8859-1 to '?', which, for the purpose
     * of counting, is alright. It's much faster than mb_strlen.
     *
     * @see http://www.php.net/strlen
     * @param string $str A UTF-8 string
     * @return mixed integer length of a string
     */
    private function utf8_strlen($str) {
        if (extension_loaded('mbstring')) {
            // If string overloading is active, it will break many of the native implementations
            // MB_OVERLOAD_STRING (integer) is a constant defined by the mbstring extension 
            // http://php.net/mbstring.func-overload
            // Possible values are 0,1,2,4 or combination of them.
            // For example, 7 for overload everything.
            // 0: No overload
            // 1: Overload mail() function
            // 2: Overload str*() functions
            // 4: Overload ereg*() functions
            // & is bitwise AND. && is logical AND.
            if (ini_get('mbstring.func_overload') & MB_OVERLOAD_STRING) {
                exit('String functions are overloaded by mbstring, must be set to 0, 1 or 4 in php.ini for PHP_UTF8 to work.');
            }
            
            // Also need to check we have the correct internal mbstring encoding.
            // The Mbstring functions assume mbstring internal encoding is set to UTF-8.
            mb_language('uni');
            mb_internal_encoding('UTF-8');
            
            if (function_exists('mb_strlen')) {
                return mb_strlen($str);
            }
        } else {
            return strlen(utf8_decode($this->bad_utf8_clean($str)));
        }
    }
    
    /**
     * Strips out any bad bytes from a UTF-8 string and returns the rest.
     * Can optionally replace bad bytes with an alternative character.
     *
     * PCRE Pattern to locate bad bytes in a UTF-8 string comes from W3 FAQ: Multilingual Forms.
     * Note: modified to include full ASCII range including control chars
     *
     * @see http://www.w3.org/International/questions/qa-forms-utf-8
     * @param string $str
     * @return string
     */
    private function bad_utf8_clean($str, $replace = FALSE) {
        $new_str = $str;
        
        // PCRE Pattern to locate bad bytes in a UTF-8 string.
        $bad_utf_pattern =
            '([\x00-\x7F]'.                          # ASCII (including control chars)
            '|[\xC2-\xDF][\x80-\xBF]'.               # Non-overlong 2-byte
            '|\xE0[\xA0-\xBF][\x80-\xBF]'.           # Excluding overlongs
            '|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}'.    # Straight 3-byte
            '|\xED[\x80-\x9F][\x80-\xBF]'.           # Excluding surrogates
            '|\xF0[\x90-\xBF][\x80-\xBF]{2}'.        # Planes 1-3
            '|[\xF1-\xF3][\x80-\xBF]{3}'.            # Planes 4-15
            '|\xF4[\x80-\x8F][\x80-\xBF]{2}'.        # Plane 16
            '|(.{1}))'                               # Invalid byte
        ;
        
        while (preg_match('/'.$bad_utf_pattern.'/S', $str, $matches)) {
            if ( ! isset($matches[2])) {
                $new_str = $matches[0];
            } elseif ($replace !== FALSE && is_string($replace)) {
                $new_str = $replace;
            }
            $str = substr($str, strlen($matches[0]));
        }
        
        return $new_str;
    }
    
    /**
     * Swap back in all the tags hashed by hash_html_blocks.
     *
     * @param string
     * @return string
     */
    private function unhash($text) {
        return preg_replace_callback(
            '/(.)\x1A[0-9]+\1/', 
            // An anonymous function as callback from PHP 5.3.0
            // But $this can be used in anonymous functions from PHP 5.4.0
            // We have to use the old-fashioned callback here
            array($this, 'unhash_callback'),
            $text
        );
    }

    /**
     * Callback
     *
     * @param array
     * @return string
     */
    private function unhash_callback($matches) {
        return $this->html_hashes[$matches[0]];
    }
    
}

/* End of file: ./system/libraries/markdown/markdown_library.php */
