<?php     
defined('C5_EXECUTE') or die(_("Access Denied."));
$uh = Loader::helper('concrete/urls');
?>
<style type="text/css">
.dd-instructions { 
	display:none;
	padding-bottom:15px;
}

.dd-instructions ul {
	margin-left:35px;
}
</style>
<h1><span><?php     echo t('Data Display Help')?></span></h1>
<div class="ccm-dashboard-inner">
If you have read through these instructions and are still having trouble, please feel free to contact us <a href="mailto:sixeightmedia@gmail.com">sixeightmedia@gmail.com</a>.<br /><br />
<hr />
<a href="javascript:void(0);" onclick="$('.dd-instructions').slideToggle();" style="float:right">Toggle All</a>
<h2><a href="javascript:void(0);" onclick="$('#creating-forms').slideToggle();">Creating Forms</a></h2>
<div class="dd-instructions" id="creating-forms">
	<ol>
		<li>From the Concrete5 Dashboard, go to Data Display &raquo; Forms.</li>
		<li>Enter a name for your new form and click &ldquo;Create Form.&rdquo;</li>
		<li>Add questions to the form by clicking &ldquo;Edit&rdquo; on the form that has been created.</li>
	</ol>
	<strong><em>Notes: </em></strong>
	<ul>
		<li>This page creates an instance of the form block on this page, and therefore these forms are managed exactly like form blocks are on other pages on your Concrete5 site. </li>
		<li>Forms may be used to gather data publicly by copying them to the Scrapbook and adding them to a page on your site.&nbsp; In such instances, you may want to require approval on public submissions (see Managing Data and Data Display Block: Other General Settings)</li>
	</ul>
</div>
<h2><a href="javascript:void(0);" onclick="$('#adding-data').slideToggle();">Adding Data</a></h2>
<div class="dd-instructions" id="adding-data">
	<ol>
		<li>Click on a form’s name in order to display the form.</li>
   		<li>Enter data by filling out the form and submitting it.</li>
	</ol>
	<h3>Notes:</h3>
	<ul>
		<li>You may also allow public users to submit data by copying the form to the scrapbook and putting the form on a public page.</li>
	</ul>
</div>
<h2><a href="javascript:void(0);" onclick="$('#managing-data').slideToggle();">Managing Data</a></h2>
<div class="dd-instructions" id="managing-data">
<ol>
<li>From the Concrete5 Dashboard, go to Data Display &rarr; Data.</li>
<li>Select the form whose data you want to view/manage from the dropdown box, and click &ldquo;Load Form Data&rdquo;.</li>

<li>To edit data, click the edit icon on the right-hand side of the data.</li>
<li>To delete an entire record, click &ldquo;Delete&rdquo; below the record.</li>
</ol>
<p><em>Notes:</em></p>
<ul>
<li>If you are allowing the data to be gathered publicly (by copying a form to the Scrapbook and having it on a public page), you may Approve/Unapprove of submissions by clicking the Approve/Unapprove button below the submission.</li>
</ul>
</div>
<h2><a href="javascript:void(0);" onclick="$('#how-templates-work').slideToggle();">Understanding Templates</a></h2>
<div class="dd-instructions" id="how-templates-work">
Data Display Templates are sections of HTML (and/or Javascript and CSS) that are used to display data from your forms.  Within the "content" sections of each template, you put placeholders.  Standard field placeholders appear as
<code><?php     echo htmlentities('<field name="My Field Name" />'); ?></code>. Detailed information about placeholders can be found in the "Placholders" section below.<br /><br />
There are two types of Data Display templates:<br /><br />
<strong>List Templates</strong><br />
List templates are used when display multiple records from a single form.  A list template consists of a header, content, alternate content (optional), and a footer.  When displaying
data with a list template, the header is first displayed first.  Then, for each form record, the content section is outputted, with the placeholders being replaced by the actual record data.
If alternate content is provided, the block alternates between using the regular content and the alternate content.  Once all records have been outputted, the footer is displayed.<br /><br />
<strong>Detail Templates</strong><br />
Detail template essentially work exactly like list templates, except that detail templates display only one record.  Detail templates only have a single section for content, (no header or footer) since the content will only be displayed once.
</div>
<h2><a href="javascript:void(0);" onclick="$('#list-templates').slideToggle();">List Templates</a></h2>
<div class="dd-instructions" id="list-templates">
<p>List Templates are used to display a listing of the form data that you have created using the Data Display package.&nbsp; List Templates are created from the back-end, but are displayed using the Data Display block.<br /><br /><strong>A List Template consists of four HTML sections:</strong></p>
<ol>
<li>Header &ndash; the header is a section of HTML code that is displayed only once at the top of the listing.&nbsp; No placeholders can be added to the header.</li>
<li>Content &ndash; the content is the section of HTML code that repeats for each item being listed.&nbsp; Placeholders are added to the content section that are replaced with the actual data when the template is displayed using the Data Display block.</li>

<li>Alternate Content &ndash; works exactly like the previous content section, except that if alternate content is defined, the Data Display block will alternate between the content section and the alternate content section as is lists out the data.&nbsp; This is useful for displaying striped rows in a table or achieving similar effects.</li>
<li>Footer &ndash; the footer is a section of HTML code that is displayed only once at the bottom of the listing.&nbsp; No placeholders can be added to the footer.</li>
</ol>
<b>IMPORTANT NOTE: Content and alternate content MUST be well-formed XML or the block will not be able to parse the template.</b>
</div>
<h2><a href="javascript:void(0);" onclick="$('#detail-templates').slideToggle();">Detail Templates</a></h2>
<div class="dd-instructions" id="detail-templates">
Detail Templates are very similar to List Templates, except that Detail Templates display information for a single item from your data, as opposed to displaying multiple items like the List Template.  As a result, rather than having four HTML sections, the Detail Template only has a single section in which HTML and placeholders may be added.<br /><br />
<b>IMPORTANT NOTE: Detail Template content MUST be well-formed XML or the block will not be able to parse the template.</b>
</div>
<h2><a href="javascript:void(0);" onclick="$('#placeholders').slideToggle();">Placeholders</a></h2>
<div class="dd-instructions" id="placeholders">
The following placeholders may be used in any content or alternate content sections of list or detail templates:<br /><br />
<br />
<strong><code><?php     echo htmlentities('<field />'); ?></code></strong><br />
Used to display a field from the form.<br /><br />
<b>Attributes</b><br />
<b>name</b> - The name of the field to be displayed.<br />
<b>maxlength</b> - Optional. The maximum number of characters to display from the field's value.  Using the maxlength attribute will strip out any HTML from the field value.<br />
<b>format</b> - Optional. Used to display a field value in different numeric formats. <code><?php     echo htmlentities('<field format="number" />'); ?></code> runs the PHP number_format function on the field value.  <code><?php     echo htmlentities('<field format="money" />'); ?></code> runs the PHP number_format function with two decimal places.<br />
<b>placeholder</b> - Optional. See section "The Placeholder Attribute".<br /><br />

<b>Example</b><br />
<code><?php     echo htmlentities('<field name="Phone Number" />'); ?></code><br />
<code><?php     echo htmlentities('<field name="Article" maxlength="100" />'); ?></code><br />
<code><?php     echo htmlentities('<field name="Price" format="money" />'); ?></code><br />
<hr />
<strong><code><?php     echo htmlentities('<timestamp />'); ?></code></strong><br />
Used to display the time/date that the record was submitted.<br /><br />
<b>Attributes</b><br />
<b>format</b> - the format in which the timestamp should be displayed.  This value is passed as the "format" parameter to the <a href="http://php.net/manual/en/function.date.php">PHP date function</a>.<br /><br />
<b>Example</b><br />
<code><?php     echo htmlentities('<timestamp format="F j, Y, g:i a" />'); ?></code><br /><br />
<hr />
<strong><code><?php     echo htmlentities('<answerid />'); ?></code></strong><br />
Displays the answer ID associated with the record.  Each answer ID is unique to each form record within a single site.  This can be useful when placed within an HTML id attribute to create unique HTML identifiers for each record (Note: the placeholder attribute would need to be used to put the answer ID within an HTML id attribute [see section "The Placeholder Attribute" below]).<br /><br />
<b>Attributes</b><br />
<em>None</em><br /><br />
<b>Example</b><br />
<code><?php     echo htmlentities('<answerid />'); ?></code><br /><br />
<hr />
<strong><code><?php     echo htmlentities('{{DETAILURL}}'); ?></code></strong><br />
Outputs the URL to details for a particular record.  Can only be used on list templates. Most useful when placed within an href attribute on a link.<br /><br />
<b>Attributes</b><br />
<em>None</em><br /><br />
<b>Example</b><br />
<code><?php     echo htmlentities('<a href="{{DETAILURL}}">View Details</a>'); ?></code><br /><br />
<hr />
<strong><code><?php     echo htmlentities('{{LISTURL}}'); ?></code></strong><br />
Outputs the URL to return back to the list.  Can only be used on detail templates. Most useful when placed within an href attribute on a link.<br /><br />
<b>Attributes</b><br />
<em>None</em><br /><br />
<b>Example</b><br />
<code><?php     echo htmlentities('<a href="{{LISTURL}}">Return to Listing</a>'); ?></code><br /><br />
<hr />
<strong><code><?php     echo htmlentities('<if></if>,<else></else>'); ?></code></strong><br />
Used to perform conditional operations within a template.  If returned true, the inner content of this tag will be outputted.  If returned false, content within the <?php     echo htmlentities('<else>'); ?> tag will be outputted.  If no <?php     echo htmlentities('<else>'); ?> is specified, the inner content will be ignored. <?php     echo htmlentities('<else>'); ?> must be a direct descendant of <?php     echo htmlentities('<if>'); ?>.<br /><br />
<b>Attributes</b><br />
<b>name</b> - the name of the field whose value will be checked.<br />
<b>comparison</b> - Optional.  If not specified, field will only be checked to see if a value is specified (and return true if so).  Values that can be used include: "equal to", "not equal to", "greater than", "greater than or equal to", "less than", "less than or equal to", "contains", and "does not contain".<br />
<b>value</b> - Required if "comparison" is specified.  The value to check the field value against.<br /><br />
<b>Example</b><br />
<code><?php     echo htmlentities('<if name="Phone" comparison="contains" value="(601)">'); ?></code><br />
&nbsp;&nbsp;&nbsp;&nbsp;<code><?php     echo htmlentities('Looks like <field name="First Name" /> is in the 601 area code.'); ?></code><br />
&nbsp;&nbsp;&nbsp;&nbsp;<code><?php     echo htmlentities('<else>'); ?></code><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<code><?php     echo htmlentities('<field name="First Name" /> is not in the 601 area code.'); ?></code><br />
&nbsp;&nbsp;&nbsp;&nbsp;<code><?php     echo htmlentities('<else>'); ?></code><br />
<code><?php     echo htmlentities('</if>'); ?></code><br />
</div>
<h2><a href="javascript:void(0);" onclick="$('#placeholder-attribute').slideToggle();">The Placeholder Attribute</a></h2>
<div class="dd-instructions" id="placeholder-attribute">
Occasionally you will want to put a field value inside of an HTML attribute.  However, doing so would cause the template to contain invalid XML, and thus cause errors in parsing the template. In order to achieve this purpose, we created a special "placeholder" attribute that can be put inside of a field, timestamp, or answerid tag.  The format is as follows:<br /><br />
<code><?php     echo htmlentities('<field name="Photo" placeholder="my-photo" />'); ?></code><br /><br />
<b>When using the "placeholder" attribute, the field will NOT be outputted when the template is parsed.</b> Instead, the field's value will be replaced in any instance of {{placeholder value}} that is found throughout the template.<br /><br />
So for example, we could later put the following into our template:<br /><br />
<code><?php     echo htmlentities('<img src="{{my-photo}}" />'); ?></code><br /><br />
Note that the placeholder must have two curly brackets on each side.<br /><br />
This is also useful when creating unique HTML id's using the "answerid" tag.  This could be achieved using:<br /><br />
<code><?php     echo htmlentities('<answerid placeholder="answerid" />'); ?></code><br />
<code><?php     echo htmlentities('<div id="{{answerid}}"><field name="First Name /></div>'); ?></code><br /><br />
</div>
<h2><a href="javascript:void(0);" onclick="$('#dd-block').slideToggle();">Data Display Block</a></h2>
<div class="dd-instructions" id="dd-block">
The Data Display block is where the forms, data, and templates that you created come together and are displayed on your site.&nbsp; The Data Display block is added to any page, just like any other Concrete5 block.<br /><br />
<b><a name="required-settings"></a>Required Settings</b><br />
The only required settings in order to display data are &ldquo;Selected Form&rdquo;, &ldquo;List Template&rdquo;, and &ldquo;Detail Template&rdquo;.&nbsp; As a result you must first create at least one of each of these items in order to use the Data Display block.<br /><br />
<b><a name="other-general-settings"></a>Other General Settings</b><br /><br />
<ul>
<li>Show Approved Items Only &ndash; Useful when gathering data from a publicly accessible form.&nbsp; Displays only items that have been approved on the Data Display &rarr; Data page.</li>
<li>Items Per Page &ndash; Limits the number of items shown on the list template</li>
<li>Show Paginator &ndash; Displays links to page through the data if necessary</li>
<li>Display Details On/Details Page &ndash; Details may be displayed on an alternate page on your site when a detail URL is clicked.&nbsp; The Data Display block must exist on the selected page.</li>
</ul><br />
<b><a name="searching"></a>Searching</b><br />
If searching is enabled, a search box is display that allows users to search results.&nbsp; The search will parse only the fields selected in the &ldquo;Fields to Search Box&rdquo;.<br /><br />

<b><a name="sorting"></a>Sorting</b><br />
You can change the default sorting of the block to sort by any field in the selected form.&nbsp; You can also also users to sort according to the fields selected in the &ldquo;User Sortable Fields Box&rdquo;.<br />
</div>
</div>