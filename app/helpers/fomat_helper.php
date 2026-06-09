<?php

function formatVND($price)
{
    return number_format($price, 0, ',', '.') . ' ₫';
}
