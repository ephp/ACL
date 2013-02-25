<?php

namespace Ephp\ACLBundle\Utility;

class Facebook {

    public static function registredAndFriends($friends, $reg_friends) {
        $out = array('registred' => array());
        foreach ($reg_friends as $reg_friend) {
            /* @var $reg_friend User */
            foreach ($friends as $id => $friend) {
                if ($friend['id'] == $reg_friend->getFacebookId()) {
                    $friend['slug'] = $reg_friend->getEmail();
                    $out['registred'][] = $friend;
                    unset($friends[$id]);
                }
            }
        }
        $out['unregistred'] = $friends;
        return $out;
    }

    public static function retriveFbId($friends) {
        $fbids = array();
        foreach ($friends as $friend) {
            $fbids[] = $friend['id'];
        }
        return $fbids;
    }

    public static function sortByName(&$array) {
        usort($$array, array($this, 'sortByName'));
    }
    
    public static function _sortByName($a, $b) {
        return strcmp($a['name'], $b['name']);
    }

}