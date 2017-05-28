<?php

namespace tigrov\country;

interface ModelInterface
{
    /**
     * Create a model for the class
     *
     * @param string $code code to create the model
     * @return static the created model
     */
    public static function create($code);

    /**
     * Get all models for the class
     *
     * @return static[] all models of the class
     */
    public static function all();

    /**
     * @return string get name of the model
     */
    public function getName();
}