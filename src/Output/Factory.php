<?php

namespace pxgamer\ArionumCLI\Output;

use function fopen;
use function fclose;
use function fputcsv;
use function ob_start;
use function array_map;
use function json_encode;
use function ob_get_clean;
use function array_combine;
use function json_last_error;
use function json_last_error_msg;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

final class Factory
{
    /** @var OutputInterface */
    private $output;

    public function setOutput(OutputInterface $output): self
    {
        $this->output = $output;

        return $this;
    }

    public function writeOutput(string $format, array $data, array $columns): void
    {
        switch ($format) {
            case Format::TABLE:
                $this->createTable($data, $columns);
                break;
            case Format::XML:
                $this->createXml($data, $columns);
                break;
            case Format::JSON:
                $this->createJson($data, $columns);
                break;
            case Format::CSV:
                $this->createCsv($data, $columns);
                break;
        }
    }

    private function createTable(array $data, array $columns): void
    {
        $table = new Table($this->output);

        $table
            ->setHeaders($columns)
            ->setRows($data)
            ->render();
    }

    private function createJson(array $data, array $columns): void
    {
        $data = array_map(function (array $row) use ($columns) {
            return array_combine($columns, $row);
        }, $data);

        $encoded = json_encode($data, JSON_PRETTY_PRINT);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('JSON encoding error: '.json_last_error_msg());
        }

        $this->output->write($encoded);
    }

    private function createXml(array $data, array $columns): void
    {
        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->preserveWhiteSpace = false;
        $document->formatOutput = true;

        $root = $document->createElement('data');
        $root = $document->appendChild($root);

        foreach ($data as $row) {
            $item = $document->createElement('item');
            $item = $root->appendChild($item);

            $row = array_combine($columns, $row);

            foreach ($row as $key => $value) {
                $item->appendChild($document->createElement($key, $value));
            }
        }

        $this->output->write($document->saveXML());
    }

    private function createCsv(array $data, array $columns): void
    {
        ob_start();
        $fd = fopen('php://output', 'wb');
        fputcsv($fd, $columns);
        foreach ($data as $row) {
            fputcsv($fd, $row);
        }
        fclose($fd);
        $csv = ob_get_clean();

        $this->output->write($csv);
    }
}
