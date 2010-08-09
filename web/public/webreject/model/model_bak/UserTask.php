 <?php
 class UserTask extends CassandraCF{
    function UserTask()
    {
       parent::CassandraCF('mall','UserTask','Super','BytesType','BytesType');
    }
 }