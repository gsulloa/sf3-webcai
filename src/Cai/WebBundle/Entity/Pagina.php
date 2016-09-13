<?php

namespace Cai\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pagina
 *
 * @ORM\Table(name="web_pagina")
 * @ORM\Entity(repositoryClass="Cai\WebBundle\Repository\PaginaRepository")
 */
class Pagina extends Publicacion
{

}
