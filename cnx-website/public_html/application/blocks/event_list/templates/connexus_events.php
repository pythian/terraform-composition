<?php

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * @var Concrete\Core\Entity\Calendar\Calendar|null $calendar may be unset in case of errors
 * @var bool $canViewCalendar
 * @var Concrete\Core\Calendar\Event\EventOccurrenceList $list
 * @var int $totalToRetrieve
 * @var int $totalPerPage
 * @var string|null $eventListTitle
 * @var string $titleFormat
 * @var Concrete\Core\Calendar\Event\Formatter\LinkFormatterInterface $linkFormatter
 * @var Concrete\Core\Calendar\Event\Formatter\DateFormatter $formatter
 * @var Concrete\Core\Page\Page|int|null $linkToPage if it's an int, it's 0
 */

if (!isset($calendar) || !$canViewCalendar) {
    return;
}
$list->setItemsPerPage($totalPerPage?$totalPerPage:5);
$pagination = $list->getPagination();
/* $pagination->setMaxPerPage($totalToRetrieve); */
$events = $pagination->getCurrentPageResults();

$service = app('helper/date');
$numEvents = count($events);
?>
<section class="filter-sec select-box">
    <div class="container">
        <?php
    if ($eventListTitle) {
        ?>
        <<?= $titleFormat ?>><?= $eventListTitle ?></<?= $titleFormat ?>>
        <?php
    }
    $months_array=array('January',
    'February',
    'March',
    'April',
    'May',
    'June',
     'July',
    'August',
    'September',
    'October',
    'November',
    'December',
 );
    ?>
        <form id="news_cat">
            <div class="select-wrap">
                <ul class="news_cat">
                <li class="init"><?=(isset($_REQUEST['month']) && @in_array($_REQUEST['month'],$months_array))?$_REQUEST['month']:'Events'?></li>
                    <?php echo $form->text('month','Events', array('readonly' => 'readonly','class' => 'form-select','placeholder' => 'Events','onClick'=>'clickEvent(this)')); ?>
                    <?php

                          for($i=1; $i<=12;$i++) {
                            $month_name = date("F", mktime(0, 0, 0, $i, 10));
                              echo '<li style="display: none;" onClick="selectEvent(this)" value="' .$month_name. '">' .$month_name. '</li>';
                          }

                      ?>
                </ul>
            </div>
        </form>
    </div>
</section>
    <section class="news-list">
            <div class="container">
        <?php
        if ($events === []) {
            ?>
        <p class="ccm-block-calendar-event-list-no-events"><?= t('There are no upcoming events.') ?></p>
        <?php
        } else {
            ?>

                <?php
            foreach ($events as $occurrence) {
                $event = $occurrence->getEvent();
                $description = $event->getDescription();
                $safe_name = strtolower($event->getName());
                $safe_name = preg_replace('/[^a-z ]/i', '', $safe_name);
                $safe_name = str_replace(' ', '+', $safe_name);
                ?>

                <div class="news-row row">
                    <div class="col-md-12 col-12 news-item">

                        <?php
                        $url = $linkFormatter->getEventOccurrenceFrontendViewLink($occurrence);
                        if ($url) {
                            ?>
                        <h3> <a href="<?= $url ?>"><?= h($event->getName()) ?></a></h3>
                        <?php
                        } else {
                            ?>
                        <h3><?= h($event->getName()) ?></h3>
                        <?php
                        }
                        ?>
<div class="connexus-event-list-event-date">
                    <span><?= $service->formatCustom('F d, Y', $occurrence->getStart()) ?></span>
                </div>



                        <?php if ($event->getAttribute('short_description')!='') {
                            ?>
                        <div class="ccm-block-page-list-description">
                            <p><?php echo $event->getAttribute('short_description') ?></p></div>
                        <?php
                            } ?>


                        <?php  if ($url) { ?>
                        <a class="btn-link" href="<?php echo h($url) ?>" title="View Event">View
                            Event</a>
                        <?php } ?>



                    </div>
                </div>
            <?php
            }
            ?>
             <?php

      if ($pagination->getTotalPages() > 1) {

          $options = array(

              'proximity'           => 2,

              'prev_message'        => 'Prev',

              'next_message'        => 'Next',

              'dots_message'        => '..',

              'active_suffix'       => '',

              'css_container_class' => 'pagination',

              'css_prev_class'      => 'prev',

              'css_next_class'      => 'next',

              'css_disabled_class'  => 'disabled',

              'css_dots_class'      => 'dots',

              'css_active_class'    => 'active'

          );

          echo $pagination->renderDefaultView($options);

      }


  ?>

    <?php
        }
        ?>
        <?php
    if ($linkToPage) {
        ?>
<div class="ccm-block-calendar-event-list-link">
    <a href="<?= $linkToPage->getCollectionLink() ?>"><?= t('View Calendar') ?></a>
</div>
<?php
    }
    ?>
            </div>
        </section>




<script>
$(document).ready(function() {
    $(".select-wrap").on("click", "ul", function() {
        $(".select-wrap").toggleClass("open");
        $(this).closest(".select-wrap ul").children('li:not(.init)').toggle();
    });
    $(".select-wrap ul li.init").text($('#category').val());
});


function selectEvent(c) {

    var d = $(c).attr('value');
    $(".select-wrap ul li.init").text(d);
    $('#month').val(d);
    //    $('.news_cat ').slideUp();

    $('#news_cat').submit();



}

function clickEvent(c) {


    if (!$(c).next().hasClass('active')) {

        $(c).addClass('active').next().slideDown().addClass('active');

    } else {

        $(c).removeClass('active').next().slideUp().removeClass('active');
    }




}
// $(document).mouseup(function(e)
// {
//     var container = $("#category");
//
//     if (!container.is(e.target) && container.has(e.target).length === 0)
//     {
//         $(container).removeClass('active').next().slideUp().removeClass('active');
//     }
// });
</script>
