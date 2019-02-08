<?php
/**
 * @author <insert name>
 * @package test
 *
 */
class Bootstrap
{
    private $class;

    public function __construct(ResultInterface $class)
    {
        $this->class = $class;
    }

    public function main()
	{
        $start = microtime(true);

        $result = $this->class->getResult();
        $finish = microtime(true);
        $delta = $finish - $start;
        $result .= $delta . ' сек.';

        return $result;
	}
}

class StatementService
{

    public function __construct($argv)
    {
        $n = 10;
        switch ($n)
        {
            case 10:
                $boot=new Bootstrap(new Statement($argv));
                $result = $boot->main();
                break;
            default:
                $boot= new ErrorMessage();
                $result = $boot->getResult();
        }
        echo $result;
    }
}

interface ResultInterface
{
    public function getResult();
}

interface MessageInterface
{
    public function getResult();
}

class ErrorMessage implements MessageInterface
{
    public function getResult()
    {
        return 'Не хватает параметров';
    }
}

abstract class AbstractStatement implements ResultInterface
{
    protected $fileName;

    public function __construct($argv)
    {
        $this->fileName = $argv[1];
    }

    public function getArray()
    {
        $file = fopen($this->fileName, 'r');

        $result = [];

        while (($line = fgetcsv($file)) !== FALSE)
        {
            $result[] = $line;
        }
        fclose($file);


        return $result;
    }
}
class Statement extends AbstractStatement
{
    private function getResultArray()
    {
        $result = [];

        foreach ($this->getArray() as $line)
        {
            $result[$line[9]][] = $line[7];
            $result[$line[9]][] = $line[8];
        }
        unset($result['Currency']);

        return $result;
    }

    public function getResult()
    {
        $result = "";

        foreach ($this->getResultArray() as $key=>$currency)
        {
            $summ = array_sum($currency);

            $result.= $key." ".$summ."\n";
        }

        return $result;
    }
}

class MoreStatement extends AbstractStatement
{
    public function getResult()
    {
        // TODO: Implement getResult() method.
    }
}

StatementService::class;

