<?php

namespace AppBundle\Console;

use AppBundle\Entity\Page;
use AppBundle\Repository\PageRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Duplication des images des pages françaises dans les autres langues
 */
class DuplicateCMSImagesCommand extends Command
{
    protected static $defaultName = 'app:page:duplicate-images-for-locales';

    protected $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry, $name = null)
    {
        $this->managerRegistry = $managerRegistry;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setDescription('Duplication des images des pages françaises dans les autres langues');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pages = $this->managerRegistry->getRepository(Page::class)->findBy(['locale' => 'fr']);

        /** @var Page $page */
        foreach ($pages as $page) {
            $content = $page->getContent(); // $content = json_decode($page->getContent());
            if (isset($content['sections'])) {
                $pagesOtherLocale = $this->managerRegistry->getRepository(Page::class)->findBy([
                    'locale' => ['es', 'en', 'de', 'it'],
                    'immutableid' => $page->getImmutableid()
                ]);

                foreach ($pagesOtherLocale as $pageOtherLocale) {
                    $contentOtherLocale = $pageOtherLocale->getContent();
                    if (isset($contentOtherLocale['sections'])) {
                        $output->writeln($pageOtherLocale->getImmutableid() . '-' . $pageOtherLocale->getLocale());
                        foreach ($contentOtherLocale['sections'] as $sectionId => $section) {
                            if (isset($content['sections'][$sectionId]['slides'])) {
                                $contentOtherLocale['sections'][$sectionId]['slides'] = $content['sections'][$sectionId]['slides'];
                                $pageOtherLocale->setContent($contentOtherLocale);
                            }
                        }
                    }
                }
            }
        }
        $this->managerRegistry->getManager()->flush();

        $output->writeln('done.');
        return 0;
    }
}
