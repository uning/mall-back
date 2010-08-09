 <?php
 class UserItem extends CassandraCF{
    function UserItem()
    {
       parent::CassandraCF('mall','UserItem',1,'BytesType','BytesType');
    }
 }