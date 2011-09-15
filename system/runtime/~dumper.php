<?php
 class Dumper { public static $xml_CDATA; public static $xml_SDATA; public static $xml_DDATA; public static $xml_count = 0; public static $xml_attrib; public static $xml_name; public static $arr_type = array('array', 'object', 'resource', 'boolean', 'NULL'); public static $collapsed = FALSE; public static $arr_history = array(); public static function dump($var, $force_type = '', $collapsed = FALSE) { if ( ! defined('DUMP_INIT')) { define('DUMP_INIT', TRUE); self::init_JS_and_CSS(); } self::$collapsed = $collapsed; switch (strtolower($force_type)) { case 'array': self::var_is_array($var); break; case 'object': self::var_is_object($var); break; case 'xml': self::var_is_xml_resource($var); break; default: self::check_type($var); } } public static function make_table_header($type, $header, $colspan = 2) { $str_i = (self::$collapsed) ? "style=\"font-style:italic\" " : ''; echo "<table cellspacing=\"2\" cellpadding=\"3\" class=\"dump_".$type."\">
				<tr>
					<td ".$str_i."class=\"dump_".$type."_header\" colspan=".$colspan." onClick='dump_toggle_table(this)'>".$header."</td>
				</tr>"; } public static function make_td_header($type, $header) { $str_d = (self::$collapsed) ? " style=\"display:none\"" : ''; echo "<tr".$str_d.">
				<td valign=\"top\" onClick='dump_toggle_row(this)' class=\"dump_".$type."_key\">".$header."</td>
				<td>"; } public static function close_td_row() { return "</td></tr>\n"; } public static function error($type) { $error = 'Error: Variable cannot be a'; if (in_array(substr($type, 0, 1), array('a', 'e', 'i', 'o', 'u', 'x'))) { $error .= 'n'; } return ($error.' '.$type.' type'); } public static function check_type($var) { if (is_resource($var)) { self::var_is_resource($var); } elseif (is_object($var)) { self::var_is_object($var); } elseif (is_array($var)) { self::var_is_array($var); } elseif (is_null($var)) { self::var_is_null(); } elseif (is_bool($var)) { self::var_is_boolean($var); } else { $var = ($var == '') ? '[empty string]' : $var; echo "<table cellspacing = \"0\"><tr>\n<td>".$var."</td>\n</tr>\n</table>\n"; } } public static function var_is_null() { echo 'NULL'; } public static function var_is_boolean($var) { $var = ($var === 1) ? 'TRUE' : 'FALSE'; echo $var; } public static function var_is_array($var) { $var_ser = serialize($var); array_push(self::$arr_history, $var_ser); self::make_table_header('array', 'array'); if (is_array($var)) { foreach ($var as $key => $value) { self::make_td_header('array', $key); if (is_array($value)) { $var_ser = serialize($value); if (in_array($var_ser, self::$arr_history, TRUE)) $value = "*RECURSION*"; } if (in_array(gettype($value), self::$arr_type)) { self::check_type($value); } else { $value = (trim($value) == '') ? '[empty string]' : $value; echo $value; } echo self::close_td_row(); } } else { echo '<tr><td>'.self::error('array').self::close_td_row(); } array_pop(self::$arr_history); echo '</table>'; } public static function var_is_object($var) { $var_ser = serialize($var); array_push(self::$arr_history, $var_ser); self::make_table_header('object', 'object'); if (is_object($var)) { $arr_obj_vars = get_object_vars($var); foreach ($arr_obj_vars as $key => $value) { $value = ( ! is_object($value) && ! is_array($value) && trim($value) == '') ? '[empty string]' : $value; self::make_td_header('object', $key); if (is_object($value) || is_array($value)) { $var_ser = serialize($value); if (in_array($var_ser, self::$arr_history, TRUE)) { $value = (is_object($value)) ? "*RECURSION* -> $".get_class($value) : "*RECURSION*"; } } if (in_array(gettype($value), self::$arr_type)) { self::check_type($value); } else { echo $value; } echo self::close_td_row(); } $arr_obj_methods = get_class_methods(get_class($var)); foreach ($arr_obj_methods as $key => $value) { self::make_td_header('object', $value); echo '[method]'.self::close_td_row(); } } else { echo '<tr><td>'.self::error('object').self::close_td_row(); } array_pop(self::$arr_history); echo '</table>'; } public static function var_is_resource($var) { self::make_table_header('resourceC', 'resource', 1); echo "<tr>\n<td>\n"; switch (get_resource_type($var)) { case 'mysql result': case 'pgsql result': $db = current(explode(' ', get_resource_type($var))); self::var_is_db_resource($var, $db); break; case 'gd': self::var_is_gd_resource($var); break; case 'xml': self::var_is_xml_resource($var); break; default: echo get_resource_type($var).self::close_td_row(); break; } echo self::close_td_row()."</table>\n"; } public static function var_is_db_resource($var, $db = 'mysql') { if ($db == 'pgsql') { $db = 'pg'; } $arr_fields = array('name', 'type', 'flags'); $num_rows = call_user_func($db.'_num_rows', $var); $num_fields = call_user_func($db.'_num_fields', $var); self::make_table_header("resource", $db." result", $num_fields + 1); echo "<tr><td class=\"dump_resource_key\">&nbsp;</td>"; for ($i = 0; $i < $num_fields; $i++) { $field_header = ''; for ($j = 0; $j < count($arr_fields); $j++) { $db_func = $db.'_field_'.$arr_fields[$j]; if (function_exists($db_func)) { $fheader = call_user_func($db_func, $var, $i). ' '; if ($j == 0) { $field_name = $fheader; } else { $field_header .= $fheader; } } } $field[$i] = call_user_func($db.'_fetch_field', $var, $i); echo "<td class=\"dump_resource_key\" title=\"".$field_header."\">".$field_name."</td>"; } echo '</tr>'; for ($i = 0; $i < $num_rows; $i++) { $row = call_user_func($db.'_fetch_array', $var, constant(strtoupper($db).'_ASSOC')); echo "<tr>\n"; echo "<td class=\"dump_resource_key\">".($i+1)."</td>"; for ($k = 0; $k < $num_fields; $k++) { $tempField = $field[$k]->name; $field_row = $row[($field[$k]->name)]; $field_row = ($field_row == '') ? "[empty string]" : $field_row; echo "<td>".$field_row."</td>\n"; } echo "</tr>\n"; } echo '</table>'; if ($num_rows>0) { call_user_func($db.'_data_seek', $var, 0); } } public static function var_is_gd_resource($var) { self::make_table_header('resource', 'gd', 2); self::make_td_header('resource', 'Width'); echo imagesx($var).self::close_td_row(); self::make_td_header('resource', 'Height'); echo imagesy($var).self::close_td_row(); self::make_td_header('resource', 'Colors'); echo imagecolorstotal($var).self::close_td_row(); echo '</table>'; } public static function var_is_xml_resource($var) { $xml_parser = xml_parser_create(); xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0); xml_set_element_handler($xml_parser, array(&$this, "xml_start_element"), array(&$this, "xml_end_element")); xml_set_character_data_handler($xml_parser, array(&$this, "xml_character_data")); xml_set_default_handler($xml_parser, array(&$this, "xml_default_handler")); self::make_table_header('xml', 'XML Document', 2); self::make_td_header('xml', 'Root'); $xml_file = ( ! ($fp = @fopen($var, "r"))) ? FALSE : TRUE; if ($xml_file) { while ($data = str_replace("\n", '', fread($fp, 4096))) self::xml_parse($xml_parser, $data, feof($fp)); } else { if ( ! is_string($var)) { echo self::error('xml').self::close_td_row()."</table>\n"; return; } $data = $var; $this->xml_parse($xml_parser, $data, 1); } echo self::close_td_row()."</table>\n"; } public static function xml_parse($xml_parser, $data, $bFinal) { if ( ! xml_parse($xml_parser, $data, $bFinal)) { die(sprintf("XML error: %s at line %d\n", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser))); } } public static function xml_start_element($parser, $name, $attribs) { self::$xml_attrib[self::$xml_count] = $attribs; self::$xml_name[self::$xml_count] = $name; self::$xml_SDATA[self::$xml_count] = 'self::make_table_header("xml", "Element", 2);'; self::$xml_SDATA[self::$xml_count] .= 'self::make_td_header("xml", "Name");'; self::$xml_SDATA[self::$xml_count] .= 'echo "<strong>'.self::$xml_name[self::$xml_count].'</strong>".self::close_td_row();'; self::$xml_SDATA[self::$xml_count] .= 'self::make_td_header("xml", "Attributes");'; if (count($attribs)>0) { self::$xml_SDATA[self::$xml_count] .= 'self::var_is_array(self::$xml_attrib['.self::$xml_count.']);'; } else { self::$xml_SDATA[self::$xml_count] .= 'echo "&nbsp;";'; } self::$xml_SDATA[self::$xml_count] .= 'echo self::close_td_row();'; self::$xml_count++; } public static function xml_end_element($parser, $name) { for ($i = 0; $i < self::$xml_count; $i++) { eval(self::$xml_SDATA[$i]); self::make_td_header('xml', 'Text'); echo ( ! empty(self::$xml_CDATA[$i])) ? self::$xml_CDATA[$i] : '&nbsp;'; echo self::close_td_row(); self::make_td_header('xml', 'Comment'); echo ( ! empty(self::$xml_DDATA[$i])) ? self::$xml_DDATA[$i] : '&nbsp;'; echo self::close_td_row(); self::make_td_header('xml', 'Children'); unset(self::$xml_CDATA[$i], self::$xml_DDATA[$i]); } echo self::close_td_row(); echo '</table>'; self::$xml_count = 0; } public static function xml_character_data($parser,$data) { $count = self::$xml_count - 1; if ( ! empty(self::$xml_CDATA[$count])) { self::$xml_CDATA[$count] .= $data; } else { self::$xml_CDATA[$count] = $data; } } public static function xml_default_handler($parser, $data) { $data = str_replace(array("&lt;!--", "--&gt;"), '', htmlspecialchars($data)); $count = self::$xml_count - 1; if ( ! empty(self::$xml_DDATA[$count])) { self::$xml_DDATA[$count] .= $data; } else { self::$xml_DDATA[$count] = $data; } } public static function init_JS_and_CSS() { echo <<<SCRIPTS
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