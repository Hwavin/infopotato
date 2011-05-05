<?php
/*********************************************************************************************************************\
 * LAST UPDATE
 * ============
 * March 22, 2007
 *
 *
 * AUTHOR
 * =============
 * Kwaku Otchere 
 * ospinto@hotmail.com
 * 
 * Thanks to Andrew Hewitt (rudebwoy@hotmail.com) for the idea and suggestion
 * 
 * All the credit goes to ColdFusion's brilliant cfdump tag
 * Hope the next version of PHP can implement this or have something similar
 * I love PHP, but var_dump BLOWS!!!
 *
 * FOR DOCUMENTATION AND MORE EXAMPLES: VISIT http://dbug.ospinto.com
 *
 *
 * PURPOSE
 * =============
 * Dumps/Displays the contents of a variable in a colored tabular format
 * Based on the idea, javascript and css code of Macromedia's ColdFusion cfdump tag
 * A much better presentation of a variable's contents than PHP's var_dump and print_r functions
 *
 *
 * USAGE
 * =============
 * new Dump( variable [,force_type] );
 * example:
 * new Dump( $myVariable );
 *
 * 
 * if the optional "force_type" string is given, the variable supplied to the 
 * function is forced to have that force_type type. 
 * example: new dBug( $myVariable , "array" );
 * will force $myVariable to be treated and dumped as an array type, 
 * even though it might originally have been a string type, etc.
 *
 * NOTE!
 * ==============
 * force_type is REQUIRED for dumping an xml string or xml file
 * new Dump( $strXml, "xml" );
 * 
\*********************************************************************************************************************/

class Dump {
	
	public $xmlDepth = array();
	public $xmlCData;
	public $xmlSData;
	public $xmlDData;
	public $xml_count = 0;
	public $xml_attrib;
	public $xml_name;
	public $arr_type = array("array", "object", "resource", "boolean", "NULL");
	public $bInitialized = FALSE;
	public $bCollapsed = FALSE;
	public $arr_history = array();
	
	//constructor
	public function __construct($var, $force_type = "", $bCollapsed = FALSE) {
		//include js and css scripts
		if ( ! defined('BDBUGINIT')) {
			define("BDBUGINIT", TRUE);
			$this->init_JS_and_CSS();
		}
		$arr_accept = array("array", "object", "xml"); //array of variable types that can be "forced"
		$this->bCollapsed = $bCollapsed;
		if (in_array($force_type, $arr_accept))
			$this->{'var_is_'.strtolower($force_type)}($var);
		else
			$this->check_type($var);
	}

	//get variable name
	public function get_variable_name() {
		$arrBacktrace = debug_backtrace();

		//possible 'included' functions
		$arrInclude = array("include", "include_once", "require", "require_once");
		
		//check for any included/required files. if found, get array of the last included file (they contain the right line numbers)
		for ($i = count($arrBacktrace)-1; $i >= 0; $i--) {
			$arrCurrent = $arrBacktrace[$i];
			if (array_key_exists("function", $arrCurrent) && 
				(in_array($arrCurrent["function"], $arrInclude) || (0 != strcasecmp($arrCurrent["function"], "dump"))))
				continue;

			$arrFile = $arrCurrent;
			
			break;
		}
		
		if (isset($arrFile)) {
			$arrLines = file($arrFile["file"]);
			$code = $arrLines[($arrFile["line"]-1)];
	
			//find call to dBug class
			preg_match('/\bnew Dump\s*\(\s*(.+)\s*\);/i', $code, $arrMatches);
			
			return $arrMatches[1];
		}
		return '';
	}
	
	//create the main table header
	public function make_table_header($type, $header, $colspan = 2) {
		if ( ! $this->bInitialized) {
			$header = $this->get_variable_name() . " (" . $header . ")";
			$this->bInitialized = TRUE;
		}
		$str_i = ($this->bCollapsed) ? "style=\"font-style:italic\" " : ""; 
		
		echo "<table cellspacing=2 cellpadding=3 class=\"dBug_".$type."\">
				<tr>
					<td ".$str_i."class=\"dBug_".$type."Header\" colspan=".$colspan." onClick='dBug_toggleTable(this)'>".$header."</td>
				</tr>";
	}
	
	//create the table row header
	public function make_td_header($type, $header) {
		$str_d = ($this->bCollapsed) ? " style=\"display:none\"" : "";
		echo "<tr".$str_d.">
				<td valign=\"top\" onClick='dBug_toggleRow(this)' class=\"dBug_".$type."Key\">".$header."</td>
				<td>";
	}
	
	//close table row
	public function close_td_row() {
		return "</td></tr>\n";
	}
	
	//error
	public function  error($type) {
		$error = "Error: Variable cannot be a";
		// this just checks if the type starts with a vowel or "x" and displays either "a" or "an"
		if (in_array(substr($type,0,1),array("a", "e", "i", "o", "u", "x"))) {
			$error.="n";
		}
		return ($error." ".$type." type");
	}

	//check variable type
	public function check_type($var) {
		switch (gettype($var)) {
			case 'resource':
				$this->var_is_resource($var);
				break;
			case 'object':
				$this->var_is_object($var);
				break;
			case 'array':
				$this->var_is_array($var);
				break;
			case 'NULL':
				$this->var_is_NULL();
				break;
			case 'boolean':
				$this->var_is_boolean($var);
				break;
			default:
				$var = ($var == '') ? "[empty string]" : $var;
				echo "<table cellspacing=0><tr>\n<td>".$var."</td>\n</tr>\n</table>\n";
				break;
		}
	}
	
	//if variable is a NULL type
	public function var_is_NULL() {
		echo 'NULL';
	}
	
	//if variable is a boolean type
	public function var_is_boolean($var) {
		$var = ($var == 1) ? 'TRUE' : 'FALSE';
		echo $var;
	}
			
	//if variable is an array type
	public function var_is_array($var) {
		$var_ser = serialize($var);
		array_push($this->arr_history, $var_ser);
		
		$this->make_table_header("array", "array");
		if (is_array($var)) {
			foreach ($var as $key => $value) {
				$this->make_td_header("array", $key);
				
				//check for recursion
				if (is_array($value)) {
					$var_ser = serialize($value);
					if (in_array($var_ser, $this->arr_history, TRUE))
						$value = "*RECURSION*";
				}
				
				if (in_array(gettype($value), $this->arr_type)) {
					$this->check_type($value);
				} else {
					$value=(trim($value) == "") ? "[empty string]" : $value;
					echo $value;
				}
				echo $this->close_td_row();
			}
		}
		else echo "<tr><td>".$this->error("array").$this->close_td_row();
		array_pop($this->arr_history);
		echo "</table>";
	}
	
	//if variable is an object type
	public function var_is_object($var) {
		$var_ser = serialize($var);
		array_push($this->arr_history, $var_ser);
		$this->make_table_header("object", "object");
		
		if (is_object($var)) {
			$arrObjVars = get_object_vars($var);
			foreach ($arrObjVars as $key => $value) {

				$value=( ! is_object($value) && ! is_array($value) && trim($value) == "") ? "[empty string]" : $value;
				$this->make_td_header("object", $key);
				
				//check for recursion
				if (is_object($value) || is_array($value)) {
					$var_ser = serialize($value);
					if (in_array($var_ser, $this->arr_history, TRUE)) {
						$value = (is_object($value)) ? "*RECURSION* -> $".get_class($value) : "*RECURSION*";

					}
				}
				if (in_array(gettype($value), $this->arr_type)) {
					$this->check_type($value);
				}
				else echo $value;
				echo $this->close_td_row();
			}
			$arrObjMethods = get_class_methods(get_class($var));
			foreach ($arrObjMethods as $key => $value) {
				$this->make_td_header("object", $value);
				echo "[function]".$this->close_td_row();
			}
		}
		else echo "<tr><td>".$this->error("object").$this->close_td_row();
		array_pop($this->arr_history);
		echo '</table>';
	}

	//if variable is a resource type
	public function var_is_resource($var) {
		$this->make_table_header("resourceC", "resource",1);
		echo "<tr>\n<td>\n";
		switch (get_resource_type($var)) {
			case "fbsql result":
			case "mssql result":
			case "msql query":
			case "pgsql result":
			case "sybase-db result":
			case "sybase-ct result":
			case "mysql result":
				$db=current(explode(" ", get_resource_type($var)));
				$this->var_is_db_resource($var, $db);
				break;
			case "gd":
				$this->var_is_gd_resource($var);
				break;
			case "xml":
				$this->var_is_xml_resource($var);
				break;
			default:
				echo get_resource_type($var).$this->close_td_row();
				break;
		}
		echo $this->close_td_row()."</table>\n";
	}

	//if variable is a database resource type
	public function var_is_db_resource($var, $db = "mysql") {
		if ($db == "pgsql") {
			$db = "pg";
		}
		if ($db == "sybase-db" || $db == "sybase-ct") {
			$db = "sybase";
		}
		$arrFields = array("name","type", "flags");	
		$numrows=call_user_func($db."_num_rows", $var);
		$numfields=call_user_func($db."_num_fields", $var);
		$this->make_table_header("resource", $db." result", $numfields+1);
		echo "<tr><td class=\"dBug_resourceKey\">&nbsp;</td>";
		for ($i = 0; $i < $numfields; $i++) {
			$field_header = "";
			for ($j = 0; $j<count($arrFields); $j++) {
				$db_func = $db."_field_".$arrFields[$j];
				if (function_exists($db_func)) {
					$fheader = call_user_func($db_func, $var, $i). " ";
					if ($j == 0) {
						$field_name = $fheader;
					} else {
						$field_header .= $fheader;
					}
				}
			}
			$field[$i] = call_user_func($db."_fetch_field", $var,$i);
			echo "<td class=\"dBug_resourceKey\" title=\"".$field_header."\">".$field_name."</td>";
		}
		echo "</tr>";
		for ($i = 0; $i < $numrows; $i++) {
			$row=call_user_func($db."_fetch_array",$var,constant(strtoupper($db)."_ASSOC"));
			echo "<tr>\n";
			echo "<td class=\"dBug_resourceKey\">".($i+1)."</td>"; 
			for ($k = 0; $k < $numfields; $k++) {
				$tempField = $field[$k]->name;
				$fieldrow = $row[($field[$k]->name)];
				$fieldrow = ($fieldrow == "") ? "[empty string]" : $fieldrow;
				echo "<td>".$fieldrow."</td>\n";
			}
			echo "</tr>\n";
		}
		echo '</table>';
		if ($numrows>0) {
			call_user_func($db."_data_seek", $var,0);
		}
	}
	
	//if variable is an image/gd resource type
	public function var_is_gd_resource($var) {
		$this->make_table_header("resource", "gd",2);
		$this->make_td_header("resource", "Width");
		echo imagesx($var).$this->close_td_row();
		$this->make_td_header("resource", "Height");
		echo imagesy($var).$this->close_td_row();
		$this->make_td_header("resource", "Colors");
		echo imagecolorstotal($var).$this->close_td_row();
		echo '</table>';
	}
	
	//if variable is an xml type
	public function var_is_xml($var) {
		$this->var_is_xml_resource($var);
	}
	
	//if variable is an xml resource type
	public function var_is_xml_resource($var) {
		$xml_parser = xml_parser_create();
		xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0); 
		xml_set_element_handler($xml_parser,array(&$this, "xmlStartElement"), array(&$this, "xmlEndElement")); 
		xml_set_character_data_handler($xml_parser, array(&$this, "xmlCharacterData"));
		xml_set_default_handler($xml_parser, array(&$this, "xmlDefaultHandler")); 
		
		$this->make_table_header("xml", "xml document",2);
		$this->make_td_header("xml", "xmlRoot");
		
		//attempt to open xml file
		$bFile = (!($fp=@fopen($var, "r"))) ? FALSE : TRUE;
		
		//read xml file
		if ($bFile) {
			while ($data = str_replace("\n", "", fread($fp,4096)))
				$this->xmlParse($xml_parser, $data, feof($fp));
		}
		//if xml is not a file, attempt to read it as a string
		else {
			if ( ! is_string($var)) {
				echo $this->error("xml").$this->close_td_row()."</table>\n";
				return;
			}
			$data = $var;
			$this->xmlParse($xml_parser, $data, 1);
		}
		
		echo $this->close_td_row()."</table>\n";
		
	}
	
	//parse xml
	public function xmlParse($xml_parser, $data, $bFinal) {
		if ( ! xml_parse($xml_parser, $data, $bFinal)) { 
				   die(sprintf("XML error: %s at line %d\n", 
							   xml_error_string(xml_get_error_code($xml_parser)), 
							   xml_get_current_line_number($xml_parser)));
		}
	}
	
	//xml: inititiated when a start tag is encountered
	public function xmlStartElement($parser, $name, $attribs) {
		$this->xml_attrib[$this->xml_count] = $attribs;
		$this->xml_name[$this->xml_count] = $name;
		$this->xmlSData[$this->xml_count] = '$this->make_table_header("xml", "xml element", 2);';
		$this->xmlSData[$this->xml_count] .= '$this->make_td_header("xml","xml_name");';
		$this->xmlSData[$this->xml_count] .= 'echo "<strong>'.$this->xml_name[$this->xml_count].'</strong>".$this->close_td_row();';
		$this->xmlSData[$this->xml_count] .= '$this->make_td_header("xml","xml_attributes");';
		if (count($attribs)>0) {
			$this->xmlSData[$this->xml_count] .= '$this->var_is_array($this->xml_attrib['.$this->xml_count.']);';
		} else {
			$this->xmlSData[$this->xml_count] .= 'echo "&nbsp;";';
		}
		$this->xmlSData[$this->xml_count] .= 'echo $this->close_td_row();';
		$this->xml_count++;
	} 
	
	//xml: initiated when an end tag is encountered
	public function xmlEndElement($parser, $name) {
		for ($i = 0; $i < $this->xml_count; $i++) {
			eval($this->xmlSData[$i]);
			$this->make_td_header("xml", "xmlText");
			echo ( ! empty($this->xmlCData[$i])) ? $this->xmlCData[$i] : "&nbsp;";
			echo $this->close_td_row();
			$this->make_td_header("xml", "xmlComment");
			echo ( ! empty($this->xmlDData[$i])) ? $this->xmlDData[$i] : "&nbsp;";
			echo $this->close_td_row();
			$this->make_td_header("xml", "xmlChildren");
			unset($this->xmlCData[$i], $this->xmlDData[$i]);
		}
		echo $this->close_td_row();
		echo "</table>";
		$this->xml_count = 0;
	} 
	
	//xml: initiated when text between tags is encountered
	public function xmlCharacterData($parser,$data) {
		$count = $this->xml_count-1;
		if ( ! empty($this->xmlCData[$count]))
			$this->xmlCData[$count] .= $data;
		else
			$this->xmlCData[$count] = $data;
	} 
	
	//xml: initiated when a comment or other miscellaneous texts is encountered
	public function xmlDefaultHandler($parser, $data) {
		//strip '<!--' and '-->' off comments
		$data = str_replace(array("&lt;!--", "--&gt;"), "", htmlspecialchars($data));
		$count = $this->xml_count-1;
		if ( ! empty($this->xmlDData[$count]))
			$this->xmlDData[$count] .= $data;
		else
			$this->xmlDData[$count] = $data;
	}

	public function init_JS_and_CSS() {
		echo <<<SCRIPTS
			<script language="JavaScript">
			/* code modified from ColdFusion's cfdump code */
				function dBug_toggleRow(source) {
					var target = (document.all) ? source.parentElement.cells[1] : source.parentNode.lastChild;
					dBug_toggleTarget(target,dBug_toggleSource(source));
				}
				
				function dBug_toggleSource(source) {
					if (source.style.fontStyle=='italic') {
						source.style.fontStyle='normal';
						source.title='click to collapse';
						return 'open';
					} else {
						source.style.fontStyle='italic';
						source.title='click to expand';
						return 'closed';
					}
				}
			
				function dBug_toggleTarget(target,switchToState) {
					target.style.display = (switchToState=='open') ? '' : 'none';
				}
			
				function dBug_toggleTable(source) {
					var switchToState=dBug_toggleSource(source);
					if(document.all) {
						var table=source.parentElement.parentElement;
						for(var i=1;i<table.rows.length;i++) {
							target=table.rows[i];
							dBug_toggleTarget(target,switchToState);
						}
					}
					else {
						var table=source.parentNode.parentNode;
						for (var i=1;i<table.childNodes.length;i++) {
							target=table.childNodes[i];
							if(target.style) {
								dBug_toggleTarget(target,switchToState);
							}
						}
					}
				}
			</script>
			
			<style type="text/css">
				table.dBug_array,table.dBug_object,table.dBug_resource,table.dBug_resourceC,table.dBug_xml {
					font-family:Verdana, Arial, Helvetica, sans-serif; color:#000000; font-size:12px;
				}
				
				.dBug_arrayHeader,
				.dBug_objectHeader,
				.dBug_resourceHeader,
				.dBug_resourceCHeader,
				.dBug_xmlHeader 
					{ font-weight:bold; color:#FFFFFF; cursor:pointer; }
				
				.dBug_arrayKey,
				.dBug_objectKey,
				.dBug_xmlKey 
					{ cursor:pointer; }
					
				/* array */
				table.dBug_array { background-color:#006600; }
				table.dBug_array td { background-color:#FFFFFF; }
				table.dBug_array td.dBug_arrayHeader { background-color:#009900; }
				table.dBug_array td.dBug_arrayKey { background-color:#CCFFCC; }
				
				/* object */
				table.dBug_object { background-color:#0000CC; }
				table.dBug_object td { background-color:#FFFFFF; }
				table.dBug_object td.dBug_objectHeader { background-color:#4444CC; }
				table.dBug_object td.dBug_objectKey { background-color:#CCDDFF; }
				
				/* resource */
				table.dBug_resourceC { background-color:#884488; }
				table.dBug_resourceC td { background-color:#FFFFFF; }
				table.dBug_resourceC td.dBug_resourceCHeader { background-color:#AA66AA; }
				table.dBug_resourceC td.dBug_resourceCKey { background-color:#FFDDFF; }
				
				/* resource */
				table.dBug_resource { background-color:#884488; }
				table.dBug_resource td { background-color:#FFFFFF; }
				table.dBug_resource td.dBug_resourceHeader { background-color:#AA66AA; }
				table.dBug_resource td.dBug_resourceKey { background-color:#FFDDFF; }
				
				/* xml */
				table.dBug_xml { background-color:#888888; }
				table.dBug_xml td { background-color:#FFFFFF; }
				table.dBug_xml td.dBug_xmlHeader { background-color:#AAAAAA; }
				table.dBug_xml td.dBug_xmlKey { background-color:#DDDDDD; }
			</style>
SCRIPTS;
	}

}

// End of file: ./system/core/dump.php 