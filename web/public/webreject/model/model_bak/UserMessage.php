 <?php
 class UserMessage extends CassandraCF{
    function UserMessage()
    {
       parent::CassandraCF('mall','UserMessage',1,'TimeUUIDType','BytesType');
    }
 }