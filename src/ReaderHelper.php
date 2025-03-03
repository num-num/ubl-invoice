<?PHP

namespace NumNum\UBL;

use Doctrine\Common\Collections\ArrayCollection;

class ReaderHelper
{
    /**
     * Get the first tag with the given name from a XML Reader mixedContent
     * result that was converted to ArrayCollection
     *
     * @param string $name
     * @param ArrayCollection $collection
     * @return ?array
     */
    public static function getTag(string $name, ArrayCollection $collection)
    {
        $tags = $collection
            ->filter(function ($element) use ($name) {
                return $element['name'] === $name;
            });

        return $tags->count() > 0 ? $tags->first() : null;
    }

    /**
     * Get the first tag.value with the given name from a XML Reader mixedContent
     * result that was converted to ArrayCollection
     *
     * @param string $name
     * @param ArrayCollection $collection
     * @return ?string
     */
    public static function getTagValue(string $name, ArrayCollection $collection)
    {
        $tag = self::getTag($name, $collection);

        return $tag['value'] ?? null;
    }

    /**
     * Get the tags with a given name from a XML Reader mixedContent result
     * that was converted to ArrayCollection
     *
     * @param string $name
     * @param ArrayCollection $collection
     */
    public static function getArrayValue(string $name, ArrayCollection $collection)
    {
        return $collection
            ->filter(function ($element) use ($name) {
                return $element['name'] === $name;
            })
            ->map(function ($element) {
                return $element['value'];
            })
            ->toArray();
    }
}
