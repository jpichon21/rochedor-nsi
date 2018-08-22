<?php
namespace AppBundle\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContactService
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $kiwiHost;
    
    public function __construct(LoggerInterface $logger, ContainerInterface $container)
    {
        $this->logger = $logger;
        $this->kiwiHost = $container->getParameter('kiwi_host');
    }

     /**
     * Query kiwi api for contact duplication detection
     *
     * @param int $codco
     * @return void
     */
    public function queryDuplicate($codco)
    {
        $data = json_encode([
            'Action' => 'MSG_CoDbl',
            'CodCo' => $codco,
            'nivMin' => 10]);
        $c = curl_init($this->kiwiHost.'/kiwi.php');
        curl_setopt($c, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ));
        $result = curl_exec($c);
        $this->logger->info(json_encode(curl_getinfo($c)));
        $this->logger->info(json_encode($data));
        $this->logger->info($result);
    }
}
