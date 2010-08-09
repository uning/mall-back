 <?php
 class UserOp extends CassandraCF{
    function UserOp()
    {
       parent::CassandraCF('mall','UserOp','Super','TimeUUIDType','BytesType');
    }
 }