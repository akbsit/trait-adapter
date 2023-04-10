<?php namespace Falbar\TraitAdapter;

use ReflectionProperty;
use ReflectionClass;

use Falbar\TraitAdapter\Helper\ContainerHelper;
use function DusanKasan\Knapsack\getOrDefault;
use DusanKasan\Knapsack\Collection;

/* @property array $arMappingList */
trait AdapterTrait
{
    private array $arOriginData = [];
    private array $arCustomData = [];

    public static function make(): self
    {
        return new static();
    }

    public function mapping(array $arMappingList = []): self
    {
        $this->arMappingList = $arMappingList;

        return $this;
    }

    public function setCustom(array $arCustomData = []): self
    {
        $this->arCustomData = $arCustomData;

        return $this;
    }

    public function create(array $arData = []): ?self
    {
        $oData = Collection::from($arData);
        if ($oData->isEmpty()) {
            return null;
        }

        $this->arOriginData = $oData->toArray();

        foreach ($this->arMappingList as $name => $key) {
            if (is_int($name)) {
                $this->{$key} = $this->arOriginData[$key];
            } else {
                $this->{$name} = $this->arOriginData[$key];
            }
        }

        $oReflectionClass = new ReflectionClass($this);
        $arPublicList = $oReflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);
        if (!empty($arPublicList)) {
            foreach ($arPublicList as $oReflectionProperty) {
                $sMethod = 'set' . $this->studly($oReflectionProperty->getName()) . 'Attribute';
                if (!method_exists(static::class, $sMethod)) {
                    continue;
                }

                $this->$sMethod();
            }
        }

        $this->unsetAdapterProperty();

        return $this;
    }

    public function createCollection(array $arDataList = []): ?self
    {
        if (empty($arDataList)) {
            return null;
        }

        $oResult = Collection::from($arDataList)
            ->map(function ($arData, $iIndex) use (&$arResult) {
                $arCustomData = array_merge([
                    'item-index' => $iIndex,
                ], $this->getCustom());

                return ContainerHelper::make(static::class)
                    ->setCustom($arCustomData)
                    ->mapping($this->arMappingList)
                    ->create($arData);
            });

        $this->arCollection = $oResult->toArray();

        $this->unsetAdapterProperty();

        return $this;
    }

    public function toArray(): array
    {
        if (property_exists($this, 'arCollection')) {
            return $this->objectToAdapterArray($this->arCollection);
        }

        return $this->objectToAdapterArray($this);
    }

    protected function getOrigin(?string $sKey = null): mixed
    {
        if (empty($sKey)) {
            return $this->arOriginData;
        }

        return getOrDefault($this->arOriginData, $sKey, null);
    }

    protected function getCustom(?string $sKey = null): mixed
    {
        if (empty($sKey)) {
            return $this->arCustomData;
        }

        return getOrDefault($this->arCustomData, $sKey, null);
    }

    protected function getCustomByItemIndex(?string $sKey = null): mixed
    {
        if (empty($sKey)) {
            return null;
        }

        $arList = getOrDefault($this->arCustomData, $sKey, null);
        $iItemIndex = $this->getCustom('item-index');
        if (empty($arList) || (is_null($iItemIndex) && empty($iItemIndex)) || !is_int($iItemIndex)) {
            return null;
        }

        return getOrDefault($arList, $iItemIndex, null);
    }

    private function unsetAdapterProperty(): void
    {
        if (property_exists($this, 'arMappingList')) {
            unset($this->arMappingList);
        }

        if (property_exists($this, 'arOriginData')) {
            unset($this->arOriginData);
        }

        if (property_exists($this, 'arCustomData')) {
            unset($this->arCustomData);
        }

        if (property_exists($this, 'arCollection')) {
            $oKeyList = Collection::from((array)$this)->except(['arCollection'])->keys();
            if ($oKeyList->isNotEmpty()) {
                foreach ($oKeyList->toArray() as $sKey) {
                    unset($this->{$sKey});
                }
            }
        }
    }

    private function objectToAdapterArray(mixed $data): array
    {
        if (empty($data)) {
            return [];
        }

        $sJsonEncode = json_encode($data);

        return json_decode($sJsonEncode, true);
    }

    private function studly(string $sValue): string
    {
        $sValue = ucwords(str_replace(['-', '_'], ' ', $sValue));
        $sValue = str_replace(' ', '', $sValue);

        return $sValue;
    }
}
