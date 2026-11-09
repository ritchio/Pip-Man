<?php
readonly class GenericResPonseDto{
    public function __construct(
        public bool $success,
        public ?string $message = null,
        public ?string $redirection = null,
        public $data = null,
    ){
        
    }
}