<?php

namespace Cai\ColumnasBundle\Entity;

use Cai\WebBundle\Entity\Entrada;
use Doctrine\ORM\Mapping as ORM;

/**
 * Columna
 *
 * @ORM\Table(name="columnas_columna")
 * @ORM\Entity(repositoryClass="Cai\ColumnasBundle\Repository\ColumnaRepository")
 */
class Columna extends Entrada
{
}

