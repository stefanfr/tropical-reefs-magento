<?php

namespace TropicalReefs\PdfTemplates\Block\Invoice;

use Mageplaza\PdfInvoice\Block\PdfItems;
use Mageplaza\PdfInvoice\Model\Source\Type;

class Items extends PdfItems
{
    public function getInvoiceType(): string
    {
        return Type::INVOICE;
    }

    public function getItemBarcode(): bool
    {
        return false;
    }
}
