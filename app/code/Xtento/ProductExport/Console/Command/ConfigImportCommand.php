<?php

/**
 * Product:       Xtento_ProductExport (2.6.9)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2017-07-11T13:07:23+00:00
 * File:          app/code/Xtento/ProductExport/Console/Command/ConfigImportCommand.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Console\Command;

use Magento\Framework\App\State as AppState;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigImportCommand extends Command
{
    /**
     * @var AppState
     */
    protected $appState;

    /**
     * @var \Xtento\ProductExport\Helper\Tools\Proxy
     */
    protected $toolsHelper;

    /**
     * ConfigImportCommand constructor.
     *
     * @param AppState $appState
     * @param \Xtento\ProductExport\Helper\Tools\Proxy $toolsHelper
     */
    public function __construct(
        AppState $appState,
        \Xtento\ProductExport\Helper\Tools\Proxy $toolsHelper
    ) {
        $this->appState = $appState;
        $this->toolsHelper = $toolsHelper;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('xtento:productexport:config:import')
            ->setDescription('Import "XTENTO product export module" configuration from JSON file (functionality in admin: Product Export > Tools)')
            ->setDefinition(
                [
                    new InputArgument(
                        'file',
                        InputArgument::REQUIRED,
                        'File to read settings from. Example: /tmp/settings.json'
                    ),
                    new InputOption(
                        'updateByName',
                        '-u',
                        InputOption::VALUE_NONE,
                        'Add this parameter to update existing profiles if the profile/destination name matches'
                    )
                ]
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->appState->setAreaCode('adminhtml');
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            // intentionally left empty
        }
        echo(sprintf("[Debug] App Area: %s\n", $this->appState->getAreaCode())); // Required to avoid "area code not set" error

        $inputFile = $input->getArgument('file');
        $updateByName = $input->getOption('updateByName');

        // Counters
        $addedCounter = ['profiles' => 0, 'destinations' => 0];
        $updatedCounter = ['profiles' => 0, 'destinations' => 0];
        $errorMessage = "";

        // Load JSON settings
        $jsonData = file_get_contents($inputFile);
        if (empty($jsonData)) {
            $output->writeln(sprintf("<error>Could not read file %s or file is empty</error>", $inputFile));
            return \Magento\Framework\Console\Cli::RETURN_FAILURE;
        }

        if (!$this->toolsHelper->importSettingsFromJson($jsonData, $addedCounter, $updatedCounter, $updateByName, $errorMessage)) {
            $output->writeln(sprintf("<error>Error while importing settings: %s</error>", $errorMessage));
            return \Magento\Framework\Console\Cli::RETURN_FAILURE;
        }

        $output->writeln(sprintf(__('<info>%1 profiles have been added, %2 profiles have been updated, %3 destinations have been added, %4 destinations have been updated.<info>', $addedCounter['profiles'], $updatedCounter['profiles'], $addedCounter['destinations'], $updatedCounter['destinations'])));
        return \Magento\Framework\Console\Cli::RETURN_SUCCESS;
    }
}
