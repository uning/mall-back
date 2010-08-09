 <?php
 class UserFriend extends CassandraCF{
    function UserFriend()
    {
       parent::CassandraCF('mall','UserFriend','Super','BytesType','BytesType');
    }
 }