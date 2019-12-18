<?php

namespace AppBundle\Console;

use AppBundle\Entity\Page;
use AppBundle\Repository\PageRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Clean unwanted Page data saved prior to november2019 fix.
 */
class CleanupCMSPagesCommand extends Command
{
    protected static $defaultName = 'app:page:cleanup';

    protected $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry, $name = null)
    {
        $this->managerRegistry = $managerRegistry;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setDescription('Clean unwanted Page data saved prior to november2019 fix.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pages = $this->managerRegistry->getRepository(Page::class)->findAll();

        /** @var Page $page */
        foreach ($pages as $page) {
            $content = $page->getContent(); // $content = json_decode($page->getContent());

            if (! isset($content['sections'])) {
                continue;
            }
            foreach ($content['sections'] as $i => $section) {
                unset($section['bodyRaw']);
                $content['sections'][$i] = $section;
            }
            $page->setContent($content);
        }
        $this->managerRegistry->getManager()->flush();
        $this->managerRegistry->getConnection()->exec('DELETE FROM ext_log_entries');

        $output->writeln('done.');
        return 0;
    }
}
