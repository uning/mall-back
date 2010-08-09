 <?php
 class UserStat extends CassandraCF{
    function UserStat()
    {
       parent::CassandraCF('mall','UserStat',1,'BytesType','BytesType');
    }
 }