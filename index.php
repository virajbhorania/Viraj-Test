<?php
$file = getopt("f:");
if(ISSET($file))
{
    $filename = $file["f"];
    $delimiter=',';

    if(!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $data = array();
    $input = readline("Enter 'Device From' 'Device To' 'Latency' : ");
    $signal = explode(" ", $input);
    if(empty($signal))
    {
        echo "please enter input";
        return FALSE;
    }
    else
    {
        $deviceFrom = $signal[0];
        $deviceTo = $signal[1];
        $latencyMilli = $signal[2];
    }
    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
        {
            if(!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
        // Content of file in Array form.
        $output = null;
        $latency = 0;
        foreach($data as $key=>$value)
        {
            if($value["Device From"] == $deviceFrom)
            {
                $output .= $deviceFrom." => ";
                $latency += $value["Latency"];
                if($value["Device To"] == $deviceTo)
                {
                    $output .= $deviceTo." => " .$latency;
                    break;
                }
                else
                {
                    $deviceFrom = $value["Device To"];
                }
            }
        }
        if($latency > $latencyMilli)
            $output = "Path not found.";
        echo $output;
    }
}
else {
    echo "Please provide the file name.";
}
?>
