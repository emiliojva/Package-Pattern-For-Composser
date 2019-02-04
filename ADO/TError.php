<?php
namespace Inovuerj\ADO;
class TError
{
    function __construct($message)
    {
        $this->Message = strip_tags($message);
    }
    
    function GetError()
    {
        return $this->Message;
    }
}
?>
