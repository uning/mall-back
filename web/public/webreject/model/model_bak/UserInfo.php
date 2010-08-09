 <?php
 class UserInfo extends CassandraCF{
    function UserInfo()
    {
       parent::CassandraCF('mall','UserInfo','','BytesType','');
    }
 }