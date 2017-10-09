<?php

//----------------------------------------------------------------------------//

function jg_compact_path(String $absPath)
{
	return str_replace($_SERVER['DOCUMENT_ROOT'] . '/', '', $absPath);
}

//----------------------------------------------------------------------------//

function jg_sane_map_access(Array $array, String $key)
{
	if (array_key_exists($key, $array)) return $array[$key];
	throw new OutOfBoundsException("key '$key' does not exist");
}

//----------------------------------------------------------------------------//

function jg_log_exception(Exception $e)
{
	// this prints the file/line where jg_log_exception is called,
	// which is fairly useless. ideally it should print the line where
	// the exception was thrown (todo)
	
    $backtrace = debug_backtrace();
    
    $name = get_class($e); 
	$message = $e->getMessage();

    foreach ($backtrace as $entry) 
    {
        if ($entry['function'] == __FUNCTION__) 
        {
			$line = $entry['line']; 
			$file = jg_compact_path($entry['file']);
			
			error_log("$name: $message --- check line $line of file '$file'");
			
			return;
        }
    }
    
    error_log("error when logging exception!");
}

//----------------------------------------------------------------------------//

function jg_dump_pretty_json($exp, String $title = NULL)
{
	echo $title == NULL ? '<p>' : "<p><b>$title: </b>";
	echo json_encode($exp, JSON_PRETTY_PRINT) . '</p>';
}

//----------------------------------------------------------------------------//

?>
