 <?php
 class UserGood extends CassandraCF{
    function UserGood()
    {
       parent::CassandraCF('mall','UserGood','Super','BytesType','BytesType');
    }
 }