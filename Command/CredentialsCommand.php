<?php

/** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** **
 **             ___                                                         **
 **            /   \        _      _ _          ___                         **
 **           / /\  \ _ __ (_)_ _ (_) |_       |   \  ___    __             **
 **           \/ /  /| `_ \| | `_\| |  _| ___  | |\ \/ _ \  / /  __/        **
 **           /  / /\| |_) | | |  | | |  |___| | |/ /  __/\/ /  \__\        **
 **           \  \/ /| ,__/|_|_|  |_|_|        |___/ \___| _/   /           **
 **            \___/ |_|                                                    **
 **                                                 ____                    **
 **                    ____                       /\ ___/\                  **
 **                  /\ ___/\                     \ \___\ \                 **
 **                  \ \___\ \__________ __________\/____\/                 **
 **                   \/____\/__________|__________/\ ___/\                 **
 **                   /\___ /\                     \ \___\ \                **
 **                   \ \___\ \                     \/____\/                **
 **                    \/____\/                                             **
 **                                                                         **
 **          Jean Bordat                                                    **
 **          Since 2K10 until today                                         **
 **                                                                         **
 ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** **/

namespace SpiritDev\Bundle\OAuth2ClientBundle\Command;

use OAuth2;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CredentialsCommand extends ContainerAwareCommand {

    /**
     * Configuration function for command
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-08-08
     */
    protected function configure() {
        $this
            ->setName('spirit-dev:oauth2:credentials')
            ->setDescription('Executes OAuth2 Credentials grant');
    }

    /**
     * Execute command depending on parameters
     * @param  InputInterface  $input  Console input
     * @param  OutputInterface $output Console output
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-08-08
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        
        $credentialsClient = $this->getContainer()->get('spirit_dev_oauth2_client.credentials_client');
        $accessToken = $credentialsClient->getAccessToken();
        $output->writeln(sprintf('Obtained Access Token: <info>%s</info>', $accessToken));

        $url = 'http://cubbyholeapi.com/api/v1/offer_scales/1';
        $output->writeln(sprintf('Requesting: <info>%s</info>', $url));
        $response = $credentialsClient->fetch($url);
        $output->writeln(sprintf('Response: <info>%s</info>', var_export($response, true)));
    }
}
