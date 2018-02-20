<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 20/02/2018
 * Time: 10:23
 */

namespace App\Entity;


class Partie
{
    protected $id;
    protected $partie_nom;
    protected $main_j1; // text --> tableau JSON
    protected $main_j2; // text --> tableau JSON
    protected $id_j1; // liaison
    protected $id_j2; // liaison
    protected $score_j1; // int
    protected $score_j2; // int
    protected $tour; // "J1 ou J2" OU liaison
    protected $manche; // int
    protected $pioche; // text --> tableau avec cartes restantes
    protected $objectifs; // text --> tableau [id_objectif : id_joueur]
    protected $carte_jetee; // text --> id de la carte
    protected $action_j1; // tableau JSON
    protected $action_j2; // tableau JSON
    protected $terrain_j1; // tableau JSON
    protected $terrain_j2; // tableau JSON
    protected $debut_partie;
}