<?php


namespace SuperCoCa\JWT\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * @Command
 */
class JWTCommand extends HyperfCommand
{
    protected $name = 'jwt:publish';

    public function handle() {
        $argument = $this->input->getOption('config');
        if ($argument) {
            $this->copySource(__DIR__ . '/../../publish/jwt.php', BASE_PATH . '/config/autoload/jwt.php');
            $this->line('The jwt-auth configuration file has been generated', 'info');
        }
    }

    protected function getOptions()
    {
        return [
            ['config', NULL, InputOption::VALUE_NONE, 'Publish the configuration for jwt-auth']
        ];
    }

    protected function copySource($copySource, $toSource)
    {
        copy($copySource, $toSource);
    }
}