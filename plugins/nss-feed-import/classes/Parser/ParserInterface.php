<?php

namespace Nss\Feed\Parser;

interface ParserInterface
{


    function processItems();

    function parseSource($data);
}