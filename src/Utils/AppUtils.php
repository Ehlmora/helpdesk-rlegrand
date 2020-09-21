<?php

namespace App\Utils;

class AppUtils {

    static public function toStringForLog($object) {
        dump($object);
        if ($object == null) { 
            return '/'; 
        } else if(is_object($object)) {
            
            switch(get_class($object)) {
                
                case 'DateTime'                             : return $object->format('d/m/Y H:i:s');
                case 'Proxies\__CG__\App\Entity\TicketState': return (string) $object->getId();
                case 'App\Entity\TicketState'               : return (string) $object->getName();
                case 'App\Entity\User'                      : return (string) $object->getId();

            }

        } else { 
            
            return (string) $object;
        }
    }

    static public function readableFieldName($field) {
        switch($field) {
            case 'title'                    : return 'Titre';
            case 'App\Entity\Ticketpriority': return 'Priorité';
            case 'dateStart'                : return 'Date de début';
            case 'dateEnd'                  : return 'Date de fin';
            case 'description'              : return 'Description';
            case 'ticketState'              : return 'Etat';
            case 'user'                     : return 'Utilisateur';
            default                         : return $field;
        }
    }
}

