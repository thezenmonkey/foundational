<?php
/**
 * Class: FoundationalElementList
 * Summary
 * Description
 * @author: richardrudy
 * @package thezenmonkey\founational\Extensions  * @version:
 */


namespace thezenmonkey\foundational\Extensions;

use function GuzzleHttp\Psr7\parse_header;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\Tab;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\CompositeField;
use SilverStripe\Forms\DropdownField;
use UncleCheese\DisplayLogic\Forms\Wrapper;
use SilverStripe\Core\Config\Configurable;

class FoundationalElementList extends DataExtension
{
    use Configurable;

    private static $db = [
//        'GridDirection' => 'Varchar',
//        'Full' => 'Boolean',
//        'BlockGrid' => 'Boolean',
//        'VerticalAlignment' => 'Varchar',
//        'HorizontalAlignment' => 'Varchar',
//        'AlignCenterMiddle' => 'Varchar'

    ];

    private static $defaults = [
        'GridDirection' => 'grid-x'
    ];

    public function updateCMSFields(FieldList $fields)
    {

        parent::updateCMSFields($fields); // TODO: Change the autogenerated stub
    }

    public function updateFoundationOptions(Tab $fields) {

        $fields->removeByName('FoundationalClasses[small]');
        $fields->removeByName('FoundationalClasses[medium]');
        $fields->removeByName('FoundationalClasses[large]');
        $fields->removeByName('FoundationalClasses[xlarge]');
        $fields->removeByName('FoundationalClasses[xxlarge]');



        if($value = $this->owner->getFoundationalElementValue('alignment')) {

            if( is_array($value) ) {
                $alignVertical = ( key_exists( 'vertical', $value ) ) ? $value['align-vertical'] : '';
                $alignHorizontal = ( key_exists( 'horizontal', $value ) ) ? $value['align-horizontal'] : '';
                $alignCenterMiddle = 0;
            } else {
                $alignCenterMiddle = 1;
                $alignVertical = '';
                $alignHorizontal = '';
            }

        } else {
            $alignCenterMiddle = 0;
            $alignVertical = '';
            $alignHorizontal = '';
        }


        $directions = Config::inst()->get('Foundational','GridDirections');


        $fields->push(DropdownField::create(
            'FoundationalElementClasses[griddirection]',
            'Direction', $directions,
            $this->owner->getFoundationalElementValue('griddirection')
        ));
        $fields->push(CheckboxField::create(
            'FoundationalElementClasses[full]',
            'Full Width',
            $this->owner->getFoundationalElementValue('full')
        ));
        $fields->push(CheckboxField::create(
            'FoundationalElementClasses[align-center-middle]',
            'Align Center Middle',
            $alignCenterMiddle
        ));

        $fields->push($vertField = Wrapper::create(
            OptionsetField::create(
                'FoundationalElementClasses[align-vertical]',
                'Vertical Alignment',
                array(
                    '' => 'Auto',
                    'align-top' => 'Top',
                    'align-middle' => 'Middle',
                    'align-bottom' => 'Bottom',
                    'align-stretch' => 'Stretch'
                ), $alignVertical
            )->addExtraClass('foundational-vertical-align'))
        );

        $fields->push($horField = Wrapper::create(
            OptionsetField::create(
                'FoundationalElementClasses[align-horizontal]',
                'Horizontal Alignment',
                array(
                    '' => 'Auto',
                    'align-left' => 'Left',
                    'align-center' => 'Center',
                    'align-right' => 'Right',
                    'align-stretch' => 'Stretch'
                ), $alignHorizontal
            )->addExtraClass('foundational-horizontal-align'))
        );



        $vertField->hideIf('FoundationalElementClasses[align-center-middle]')->isChecked();




        $fields->push(
            CheckboxField::create(
                'FoundationalElementClasses[blockgrid][active]',
                'Block Grid',
                $this->owner->getFoundationalElementValue('blockgrid')
            )->setDescription(
                'All Children Form a with defined width see <a href="https://foundation.zurb.com/sites/docs/xy-grid.html#block-grids" target="_blank">Foundation Docs</a> for details'
            )
        );

        $blockGrid = CompositeField::create();

        $blockGrid->push(DropdownField::create(
            'FoundationalElementClasses[blockgrid][small]',
            'At Small Screen Sizes',
            self::generateSizeArray('small'),
            $this->owner->getFoundationalElementValue('small'))->setEmptyString('Choose a Block Count')
        );
        $blockGrid->push(DropdownField::create(
            'FoundationalElementClasses[blockgrid][medium]',
            'At Medium Screen Sizes',
            self::generateSizeArray('medium'),
            $this->owner->getFoundationalElementValue('medium'))->setEmptyString('Choose a Block Count')
        );
        $blockGrid->push(DropdownField::create(
            'FoundationalElementClasses[blockgrid][large]',
            'At Large Screen Sizes',
            self::generateSizeArray('large'),
            $this->owner->getFoundationalElementValue('large'))->setEmptyString('Choose a Block Count')
        );


        if(Config::inst()->get('Foundational', 'UseXLarge')) {
            $blockGrid->push(DropdownField::create(
                'FoundationalElementClasses[blockgrid][xlarge]',
                'At X-Large Screen Sizes',
                self::generateSizeArray('xlarge'),
                $this->owner->getFoundationalElementValue('xlarge'))->setEmptyString('Choose a Block Count')
            );
        }

        if(Config::inst()->get('Foundational', 'UseXXLarge')) {
            $blockGrid->push(DropdownField::create(
                'FoundationalElementClasses[blockgrid][xxlarge]',
                'At XX-Large Screen Sizes',
                self::generateSizeArray('xxlarge'),
                $this->owner->getFoundationalElementValue('xxlarge') )->setEmptyString('Choose a Block Count')
            );
        }

        $blockGridSizes = Wrapper::create($blockGrid);

        $blockGridSizes->displayIf('FoundationalElementClasses[blockgrid][active]')->isChecked();

        $fields->push( $blockGridSizes );

    }

    public function generateSizeArray($size) {

        $i = 1;

        while($i <= 8) {

            $sizeArray[$size.'-up-'.$i] = $i . (($i > 1) ? ' Units Across' : ' Unit Across');
            $i++;
        }

        return $sizeArray;
    }


    public function getBlockGridClasses() {

        $style = '';
        $style .= ' ' . $this->owner->Small;
        $style .= ' ' . $this->owner->Medium;
        $style .= ' ' . $this->owner->Large;
        $style .= ' ' . $this->owner->XLarge;
        $style .= ' ' . $this->owner->XXLarge;
        return $style;
    }

    public function onBeforeWrite()
    {


        parent::onBeforeWrite(); // TODO: Change the autogenerated stub
    }

}