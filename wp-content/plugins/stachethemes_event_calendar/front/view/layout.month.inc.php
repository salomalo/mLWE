<table class="stec-layout-month" cellspacing="0" cellpadding="0">
    <tbody>
        <tr class="stec-layout-month-daylabel">
            <td>
                <p>-</p> <!-- day label -->
                <p>-</p> <!-- day label short -->
                <p>-</p> <!-- day label very short -->
            </td>
            <td>
                <p>-</p>
                <p>-</p>
                <p>-</p>
            </td>
            <td>
                <p>-</p>
                <p>-</p>
                <p>-</p>
            </td>
            <td>
                <p>-</p>
                <p>-</p>
                <p>-</p>
            </td>
            <td>
                <p>-</p>
                <p>-</p>
                <p>-</p>
            </td>
            <td>
                <p>-</p>
                <p>-</p>
                <p>-</p>
            </td>
            <td>
                <p>-</p>
                <p>-</p>
                <p>-</p>
            </td>
        </tr>

        <?php for ($row = 0; $row < 6; $row++) : ?>
            <tr class="stec-layout-month-weekrow <?php if ($row == 5) { echo "stec-layout-month-weekrow-last"; } ?>">
                <?php for ($cell = 0; $cell < 7; $cell++) : ?>
                    <td class="stec-layout-month-daycell">
                        <div class="stec-layout-month-daycell-wrap">
                            <p class="stec-layout-month-daycell-num">DN_T</p>
                            <ul class="stec-layout-month-daycell-events">
                                
<!--                            <li class="stec-layout-month-daycell-event">
                                    <p class="stec-layout-month-daycell-event-name">EVENT SUMMARY</p>
                                </li>
-->
<!--                                 <li class="stec-layout-month-daycell-eventmore">
                                    <p class="stec-layout-month-daycell-eventmore-count">+0 More</p>
                                    <p class="stec-layout-month-daycell-eventmore-count-dot"></p>
                                    <p class="stec-layout-month-daycell-eventmore-count-dot"></p>
                                    <p class="stec-layout-month-daycell-eventmore-count-dot"></p>
                                </li>-->

                            </ul>
                        </div>
                    </td>
                <?php endfor; ?>
            </tr>
        <?php endfor; ?>

        <tr class="stec-layout-month-eventholder stec-event-holder">
            <td colspan="7">
                <ul class="stec-layout-events">
                    <?php
                    // Generated by JS
                    // include("layout.event.inc.php");
                    ?>
                </ul>
            </td>
        </tr>
    </tbody>
</table>