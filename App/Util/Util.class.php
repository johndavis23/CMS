<?php

namespace App\Util;
/**
 * Description of Util
 *
 * @author jdavis
 */
include_once("Config/config.php");

/*static*/ class Util 
{
    public static function error_log($message )
    {
        $first = true;
        $bts =  debug_backtrace();
        $stack_trace_string = "Stack Trace: \n";
        foreach($bts as $bt)
        {
            if(!$first){$first = false;}else{
                $stack_trace_string.="From: ". $bt['file'] . " on Line:  (". $bt['line'].")\n";
            }
        }
        error_log("\n".$message."\n".$stack_trace_string);
    }
	
    public static function error_log_stacktrace()
    {
        $first = true;
        $bts =  debug_backtrace();
        foreach($bts as $bt)
        {
            if(!$first){$first = false;}else{
                error_log("Called from: ". $bt['file'] . ' line  '. $bt['line']);
            }
        }
                 
    }
   
    public static function is_running($PID)
    {
    	if(is_int($PID))
		{
           exec("ps $PID", $ProcessState);
           return(count($ProcessState) >= 2);
		}
		return false;
    } 
    public static function curl($url) 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    } 
    public static function format_phone($phone)
    {
        $phone = preg_replace("/[^0-9]/", "", $phone);
        
        if(strlen($phone) === 7)
            return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
        else if(strlen($phone) === 10)
            return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
        else
            return $phone;
    } 
    public static function send_mail($to, $from, $subject, $msg)
    {
        $headers = "MIME-Version: 1.0 rn" ;
        $headers .= "Content-Type: text/html; \r\n" ;
        $headers .= "From: $from  \r\n" ;
        
        //some injection protection
        $illegals=array("%0A","%0D","%0a","%0d","bcc:","Content-Type","BCC:","Bcc:","Cc:","CC:","TO:","To:","cc:","to:");
        $to = str_replace($illegals, "", $to);
        $getemail = explode("@",$to);
 
        //send only if there is one email
        if(sizeof($getemail) > 2)
        {
            return false;
        }
        
        //now we are ready to send mail
        $sent = mail($to, $subject, $msg, $headers);
        if($sent){
            return true;
        }else{
            return false;
        }
    }
  
}
