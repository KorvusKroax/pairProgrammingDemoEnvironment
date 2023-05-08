<?php

class Container
{
  private $configuration;
  private array $instances;

  public function __construct(array $configuration)
  {
    $this->configuration = $configuration;
    $this->instances = [];
  }

  public function get($serviceId)
  {
    $arguments = $this->configuration['services'][$serviceId]['arguments'] ?? [];
    if (!isset($this->instances[$serviceId])) {
      foreach ($arguments as $index => $arg) {
        if (strpos($arg, '@') === 0) {
          $arguments[$index] = $this->get(substr($arg, 1));
        }
        if (strpos($arg, '%') === 0) {
          $arguments[$index] = $this->getParameter(substr($arg, 1, strlen($arg) - 2));
        }
      }

      $new_class =  $this->configuration['services'][$serviceId]['class'];
      $this->instances[$serviceId] = new $new_class(...$arguments);
    }

    return $this->instances[$serviceId];
  }

  public function getParameter($parameterName)
  {
    return $this->configuration['parameters'][$parameterName] ?? null;
  }

  public function getServicesTagged($tagName)
  {
    foreach($this->configuration['services'] as $serviceId => $serviceValue) 
    {
      if (isset($serviceValue['tags']) && in_array($tagName, $serviceValue['tags'])) {
        $result[] = $this->get($serviceId);
      }
    }

    return $result ?? [];
  }
}
