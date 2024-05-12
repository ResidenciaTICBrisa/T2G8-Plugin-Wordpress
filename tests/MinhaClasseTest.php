<?php

use PHPUnit\Framework\TestCase;

class MinhaClasseTest extends TestCase
{
    public function testSoma()
    {
        $minhaClasse = new MinhaClasse();
        $resultado = $minhaClasse->soma(2, 3);
        $this->assertEquals(8, $resultado);
    }
}

class MinhaClasse
{
    public function soma($a, $b)
    {
        return $a + $b;
    }
}