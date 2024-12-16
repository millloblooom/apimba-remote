<?xml version="1.0"?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/"
    xmlns:moz="http://www.mozilla.org/2006/browser/search/">
    <ShortName>apimba (en)</ShortName>
    <Description>apimba (en)</Description>
    <Image height="16" width="16" type="image/x-icon">favicon.ico</Image>
    <Url type="text/html" method="get" template="/wiki/Special:Search&amp;search={searchTerms}" />
    <Url type="application/x-suggestions+json" method="get"
        template="/wiki/api.php?action=opensearch&amp;search={searchTerms}&amp;namespace=0" />
    <Url type="application/x-suggestions+xml" method="get"
        template="/wiki/api.php?action=opensearch&amp;format=xml&amp;search={searchTerms}&amp;namespace=0" />
    <moz:SearchForm>/wiki/Special:Search</moz:SearchForm>
</OpenSearchDescription>