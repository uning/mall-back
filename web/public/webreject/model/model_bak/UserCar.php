 <?php
 class UserCar extends CassandraCF{
    function UserCar()
    {
       parent::CassandraCF('mall','UserCar','Super','BytesType','BytesType');
    }
 }