<?php
/**
 * Created by PhpStorm.
 * User: silver
 * Date: 16/08/17
 * Time: 14:09
 */

namespace Silver;


use DateTime;
use Exception;
use League\Csv\Reader;
use League\Csv\Writer;

class Engine
{
    /**
     * @var array
     */
    protected $args;

    /**
     * Engine constructor.
     * @param array $args
     */
    public function __construct(array $args)
    {
        $this->args = $args;
    }

    public function execute()
    {
        $reader = Reader::createFromPath($this->args['f']);
        $reader->setDelimiter(';');
        $count = $reader->each(function() {
            return true;
        });

        $builder = new Builder($this->args['s'], $count);
        $dispatcher = new Dispatcher($this->args['h']);

        $errors = [];

        foreach ($reader->fetchAssoc(0) as $row) {
            $builder->progress();

            try {
                $response = $dispatcher->send($builder->payload($row));
                $builder->setCategories($response);
            }
            catch(Exception $e) {
                $row['message'] = $e->getMessage();

                if(strpos($row['message'], 'Store') !== false) {
                    echo PHP_EOL.'[!] Warning: Store not found'.PHP_EOL;
                    exit(1);
                }

                $errors[] = $row;
            }
        }

        echo PHP_EOL.'Total errors: '.count($errors).PHP_EOL;

        if(count($errors) > 0) {
            $rows = array_map(function($value) {
                return array_values($value);
            }, $errors);

            $csvErrors = sprintf('log/%s.csv', (new DateTime())->format('His'));
            $writer = Writer::createFromPath($csvErrors, 'w+');
            $writer->setDelimiter(';');
            $writer->insertOne(array_keys($errors[0]));
            $writer->insertAll($rows);
        }
    }
}