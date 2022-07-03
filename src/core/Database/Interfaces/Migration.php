<?php

namespace MVC\Core\Database\Interfaces;

interface Migration
{
    public function up();
    public function down();
}