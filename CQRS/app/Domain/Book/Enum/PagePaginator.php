<?php

namespace App\Domain\Book\Enum;

enum PagePaginator: string
{
    case DEFAULT_HOME_PAGE_SHOW_NUM = '6';

    case DEFAULT_TABLE_SHOW_NUM     = '10';

    case DEFAULT_PAGE_START_NUM     = '1';

    case PAGE        = 'page';

    case PAGES_COUNT = 'count';

    case COLLECTION  = 'collection';

    case SHOW        = 'show';

}