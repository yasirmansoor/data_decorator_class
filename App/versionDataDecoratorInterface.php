<?php

namespace App;

interface versionDataDecoratorInterface {

    public function __construct($data = null);

    public function __destruct();

    public function hydrate($data);

    public function dehydrate();

    public function get($key);

    public function set($key, $value);

    public function toArray();

    public function keys();

    public function count();
}