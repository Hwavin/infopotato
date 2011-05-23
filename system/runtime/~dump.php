<?php
 class Dump { public $xml_CDATA; public $xml_SDATA; public $xml_DDATA; public $xml_count = 0; public $xml_attrib; public $xml_name; public $arr_type = array('array', 'object', 'resource', 'boolean', 'NULL'); public $collapsed = FALSE; public $arr_history = array(); public function __construct($var, $force_type = '', $collapsed = FALSE) { if ( ! defined('DUMP_INIT')) { define('DUMP_INIT', TRUE); self::init_JS_and_CSS(); } $this->collapsed = $collapsed; switch (strtolower($force_type)) { case 'array': $this->var_is_array($var); break; case 'object': $this->var_is_object($var); break; case 'xml': $this->var_is_xml_resource($var); break; default: $this->check_type($var); } } public function make_table_header($type, $header, $colspan = 2) { $str_i = ($this->collapsed) ? "style=\"font-style:italic\" " : ''; echo "<table cellspacing=\"2\" cellpadding=\"3\" class=\"dump_".$type."\">
				<tr>
					<td ".$str_i."class=\"dump_".$type."_header\" colspan=".$colspan." onClick='dump_toggle_table(this)'>".$header."</td>
				</tr>"; } public function make_td_header($type, $header) { $str_d = ($this->collapsed) ? " style=\"display:none\"" : ''; echo "<tr".$str_d.">
				<td valign=\"top\" onClick='dump_toggle_row(this)' class=\"dump_".$type."_key\">".$header."</td>
				<td>"; } public function close_td_row() { return "</td></tr>\n"; } public function error($type) { $error = 'Error: Variable cannot be a'; if (in_array(substr($type, 0, 1), array('a', 'e', 'i', 'o', 'u', 'x'))) { $error .= 'n'; } return ($error.' '.$type.' type'); } public function check_type($var) { if (is_resource($var)) { $this->var_is_resource($var); } elseif (is_object($var)) { $this->var_is_object($var); } elseif (is_array($var)) { $this->var_is_array($var); } elseif (is_null($var)) { $this->var_is_null(); } elseif (is_bool($var)) { $this->var_is_boolean($var); } else { $var = ($var == '') ? '[empty string]' : $var; echo "<table cellspacing = \"0\"><tr>\n<td>".$var."</td>\n</tr>\n</table>\n"; } } public function var_is_null() { echo 'NULL'; } public function var_is_boolean($var) { $var = ($var === 1) ? 'TRUE' : 'FALSE'; echo $var; } public function var_is_array($var) { $var_ser = serialize($var); array_push($this->arr_history, $var_ser); $this->make_table_header('array', 'array'); if (is_array($var)) { foreach ($var as $key => $value) { $this->make_td_header('array', $key); if (is_array($value)) { $var_ser = serialize($value); if (in_array($var_ser, $this->arr_history, TRUE)) $value = "*RECURSION*"; } if (in_array(gettype($value), $this->arr_type)) { $this->check_type($value); } else { $value = (trim($value) == '') ? '[empty string]' : $value; echo $value; } echo $this->close_td_row(); } } else { echo '<tr><td>'.$this->error('array').$this->close_td_row(); } array_pop($this->arr_history); echo '</table>'; } public function var_is_object($var) { $var_ser = serialize($var); array_push($this->arr_history, $var_ser); $this->make_table_header('object', 'object'); if (is_object($var)) { $arr_obj_vars = get_object_vars($var); foreach ($arr_obj_vars as $key => $value) { $value = ( ! is_object($value) && ! is_array($value) && trim($value) == '') ? '[empty string]' : $value; $this->make_td_header('object', $key); if (is_object($value) || is_array($value)) { $var_ser = serialize($value); if (in_array($var_ser, $this->arr_history, TRUE)) { $value = (is_object($value)) ? "*RECURSION* -> $".get_class($value) : "*RECURSION*"; } } if (in_array(gettype($value), $this->arr_type)) { $this->check_type($value); } else { echo $value; } echo $this->close_td_row(); } $arr_obj_methods = get_class_methods(get_class($var)); foreach ($arr_obj_methods as $key => $value) { $this->make_td_header('object', $value); echo '[method]'.$this->close_td_row(); } } else { echo '<tr><td>'.$this->error('object').$this->close_td_row(); } array_pop($this->arr_history); echo '</table>'; } public function var_is_resource($var) { $this->make_table_header('resourceC', 'resource', 1); echo "<tr>\n<td>\n"; switch (get_resource_type($var)) { case 'gd': $this->var_is_gd_resource($var); break; case 'xml': $this->var_is_xml_resource($var); break; default: echo get_resource_type($var).$this->close_td_row(); break; } echo $this->close_td_row()."</table>\n"; } public function var_is_gd_resource($var) { $this->make_table_header('resource', 'gd', 2); $this->make_td_header('resource', 'Width'); echo imagesx($var).$this->close_td_row(); $this->make_td_header('resource', 'Height'); echo imagesy($var).$this->close_td_row(); $this->make_td_header('resource', 'Colors'); echo imagecolorstotal($var).$this->close_td_row(); echo '</table>'; } public function var_is_xml_resource($var) { $xml_parser = xml_parser_create(); xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0); xml_set_element_handler($xml_parser, array(&$this, "xml_start_element"), array(&$this, "xml_end_element")); xml_set_character_data_handler($xml_parser, array(&$this, "xml_character_data")); xml_set_default_handler($xml_parser, array(&$this, "xml_default_handler")); $this->make_table_header('xml', 'XML Document', 2); $this->make_td_header('xml', 'Root'); $xml_file = ( ! ($fp = @fopen($var, "r"))) ? FALSE : TRUE; if ($xml_file) { while ($data = str_replace("\n", '', fread($fp, 4096))) $this->xml_parse($xml_parser, $data, feof($fp)); } else { if ( ! is_string($var)) { echo $this->error('xml').$this->close_td_row()."</table>\n"; return; } $data = $var; $this->xml_parse($xml_parser, $data, 1); } echo $this->close_td_row()."</table>\n"; } public function xml_parse($xml_parser, $data, $bFinal) { if ( ! xml_parse($xml_parser, $data, $bFinal)) { die(sprintf("XML error: %s at line %d\n", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser))); } } public function xml_start_element($parser, $name, $attribs) { $this->xml_attrib[$this->xml_count] = $attribs; $this->xml_name[$this->xml_count] = $name; $this->xml_SDATA[$this->xml_count] = '$this->make_table_header("xml", "Element", 2);'; $this->xml_SDATA[$this->xml_count] .= '$this->make_td_header("xml", "Name");'; $this->xml_SDATA[$this->xml_count] .= 'echo "<strong>'.$this->xml_name[$this->xml_count].'</strong>".$this->close_td_row();'; $this->xml_SDATA[$this->xml_count] .= '$this->make_td_header("xml", "Attributes");'; if (count($attribs)>0) { $this->xml_SDATA[$this->xml_count] .= '$this->var_is_array($this->xml_attrib['.$this->xml_count.']);'; } else { $this->xml_SDATA[$this->xml_count] .= 'echo "&nbsp;";'; } $this->xml_SDATA[$this->xml_count] .= 'echo $this->close_td_row();'; $this->xml_count++; } public function xml_end_element($parser, $name) { for ($i = 0; $i < $this->xml_count; $i++) { eval($this->xml_SDATA[$i]); $this->make_td_header('xml', 'Text'); echo ( ! empty($this->xml_CDATA[$i])) ? $this->xml_CDATA[$i] : '&nbsp;'; echo $this->close_td_row(); $this->make_td_header('xml', 'Comment'); echo ( ! empty($this->xml_DDATA[$i])) ? $this->xml_DDATA[$i] : '&nbsp;'; echo $this->close_td_row(); $this->make_td_header('xml', 'Children'); unset($this->xml_CDATA[$i], $this->xml_DDATA[$i]); } echo $this->close_td_row(); echo '</table>'; $this->xml_count = 0; } public function xml_character_data($parser,$data) { $count = $this->xml_count - 1; if ( ! empty($this->xml_CDATA[$count])) { $this->xml_CDATA[$count] .= $data; } else { $this->xml_CDATA[$count] = $data; } } public function xml_default_handler($parser, $data) { $data = str_replace(array("&lt;!--", "--&gt;"), '', htmlspecialchars($data)); $count = $this->xml_count - 1; if ( ! empty($this->xml_DDATA[$count])) { $this->xml_DDATA[$count] .= $data; } else { $this->xml_DDATA[$count] = $data; } } public static function init_JS_and_CSS() { echo <<<SCRIPTS
			<script language="JavaScript">
			/* code modified from ColdFusion's cfdump code */
				function dump_toggle_row(source) {
					var target = (document.all) ? source.parentElement.cells[1] : source.parentNode.lastChild;
					dump_toggle_target(target, dump_toggle_source(source));
				}
				
				function dump_toggle_source(source) {
					if (source.style.fontStyle == 'italic') {
						source.style.fontStyle = 'normal';
						source.title='click to collapse';
						return 'open';
					} else {
						source.style.fontStyle = 'italic';
						source.title = 'click to expand';
						return 'closed';
					}
				}
			
				function dump_toggle_target(target, switchToState) {
					target.style.display = (switchToState == 'open') ? '' : 'none';
				}
			
				function dump_toggle_table(source) {
					var switchToState = dump_toggle_source(source);
					if(document.all) {
						var table = source.parentElement.parentElement;
						for(var i=1; i<table.rows.length; i++) {
							target = table.rows[i];
							dump_toggle_target(target, switchToState);
						}
					}
					else {
						var table = source.parentNode.parentNode;
						for (var i=1; i<table.childNodes.length; i++) {
							target = table.childNodes[i];
							if(target.style) {
								dump_toggle_target(target,switchToState);
							}
						}
					}
				}
			</script>
			
			<style type="text/css">
				table.dump_array,table.dump_object,table.dump_resource,table.dump_resourceC,table.dump_xml {
					font-family:Verdana, Arial, Helvetica, sans-serif; color:#000; font-size:12px;
				}
				
				.dump_array_header,
				.dump_object_header,
				.dump_resource_header,
				.dump_resourceC_header,
				.dump_xml_header 
					{ font-weight:bold; color:#fff; cursor:pointer; }
				
				.dump_array_key,
				.dump_object_key,
				.dump_xml_key
					{ cursor:pointer; }
					
				/* array */
				table.dump_array { background-color:#006600; }
				table.dump_array td { background-color:#fff; }
				table.dump_array td.dump_array_header { background-color:#009900; }
				table.dump_array td.dump_array_key { background-color:#CCFFCC; }
				
				/* object */
				table.dump_object { background-color:#0000CC; }
				table.dump_object td { background-color:#fff; }
				table.dump_object td.dump_object_header { background-color:#4444CC; }
				table.dump_object td.dump_object_key { background-color:#CCDDFF; }
				
				/* resource */
				table.dump_resource, table.dump_resourceC { background-color:#884488; }
				table.dump_resource td, table.dump_resourceC td { background-color:#fff; }
				table.dump_resource td.dump_resource_header, table.dump_resourceC td.dump_resourceC_header { background-color:#AA66AA; }
				table.dump_resource td.dump_resource_key, table.dump_resourceC td.dump_resourceC_key { background-color:#FFDDFF; }
				
				/* xml */
				table.dump_xml { background-color:#888; }
				table.dump_xml td { background-color:#fff; }
				table.dump_xml td.dump_xml_header { background-color:#aaa; }
				table.dump_xml td.dump_xml_key { background-color:#ddd; }
			</style>
SCRIPTS;
} } 