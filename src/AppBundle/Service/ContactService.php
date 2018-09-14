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
     * Query kiwi api for contact duplication detection and return true if a duplicated contact is found
     *
     * @param int $codco
     * @return bool
     */
    public function queryDuplicate($codco)
    {
        $action = json_encode([
            'Action' => 'MSG_CoDbl',
            'CodCo' => $codco,
            'nivMin' => 10]);
        $c = curl_init($this->kiwiHost.'/kiwi.php');
        curl_setopt($c, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($c, CURLOPT_POSTFIELDS, 'Action='.$action);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded'
        ));
        $rawResult = curl_exec($c);
        $this->logger->info(json_encode(curl_getinfo($c)));
        $this->logger->info(json_encode($action));
        $this->logger->info($rawResult);
        $result = json_decode(str_replace('|||JS=retour|||', '', $rawResult), JSON_OBJECT_AS_ARRAY);
        $this->logger->info(json_encode($result));
        return (isset($result['backVal']['retour']['listCod']));
    }
}
