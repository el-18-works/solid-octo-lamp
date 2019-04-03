<?php require_once("psdocs.php"); ?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN">
<html><head>
<title>DSC</title>
<META NAME="Author" CONTENT="Satlin Luckxa.">
<META NAME="Publisher" CONTENT="Lucifer, inc.">
<META NAME="Publisher-Email" CONTENT="s4instar@yahoo.ne.jp">
<META NAME="Content-Language" CONTENT="zh-CS">
<meta name="generator" content="vim">
<style>
<?=css_minimum()?>
<?=css_psdocs()?>
</style>
</head>
<body>
<H1>PostScript Language <i>DSC</i> <small>Specification 1</small></H1>

<A NAME="Specification"></A>
<h3>PostScript Language Document Structuring Conventions Specification</h3>
As discussed in Chapter 3 of the PostScript Language Reference Manual, Second
Edition, the PostScript&trade; language standard does not specify the overall
structure of a PostScript language program. Any sequence of tokens conforming
to the syntax and semantics of the PostScript language is a valid program
that may be presented to a PostScript interpreter for execution.<BR>
For a PostScript language program that is a page description (in other words,
a description of a printable document), it is often advantageous to impose
an overall program structure.<BR>
A page description can be organized as a prolog and a script, as discussed
in section 2.4.2, &quot;Program Structure&quot; of the PostScript Language
Reference Manual, Second Edition. The prolog contains application-dependent
definitions. The script describes the particular desired results in terms
of those definitions. The prolog is written by a programmer, stored in a
place accessible to an application program, and incorporated as a standard
preface to each page description created by the application. The script
is usually generated automatically by an application program.<BR>
Beyond this simple convention, this appendix defines a standard set of document
structuring conventions (DSC). Use of the document structuring conventions
not only helps assure that a document is device independent, it allows PostScript
language programs to communicate their document structure and printing requirements
to document managers in a way that does not affect the PostScript language
page description.<BR>
A document manager can be thought of as an application that manipulates
the PostScript language document based on the document structuring con-ventions
found in it. In essence, a document manager accepts one or more PostScript
language programs as input, transforms them in some way, and produces a
PostScript language program as output. Examples of document managers include
print spoolers, font and other resource servers, post-processors, utility
programs, and toolkits.<BR>
If a PostScript language document properly communicates its structure and
requirements to a document manager, it can receive certain printing services.
A document manager can offer different types of services to a document.
If the document in question does not conform to the DSC, some or all of
these services may be denied to it.<BR>
Specially formatted PostScript language comments communicate the docu-ment
structure to the document manager. Within any PostScript language document,
any occurrence of the character % not inside a PostScript language string
introduces a comment. The comment consists of all characters between the
% and the next newline, including regular, special, space, and tab charac-ters.
The scanner ignores comments, treating each one as if it were a single white-space
character. DSC comments, which are legal PostScript language comments, do
not affect the destination interpreter in any manner.<BR>
DSC comments are specified by two percent characters (%%) as the first characters
on a line (no leading white space). These characters are immedi-ately followed
by a unique keyword describing that particular comment- again, no white
space. The keyword always starts with a capital letter and is almost always
mixed-case. For example:
<div class=code><CODE>
	%%BoundingBox: 0 0 612 792 <BR>
	%%Pages: 45 <BR>
	%%BeginSetup
</CODE></div>
Note that some keywords end with a colon (considered to be part of the keyword),
which signifies that the keyword is further qualified by options or arguments.
There should be one space character between the ending colon of a keyword
and its subsequent arguments.<BR>
The PostScript language was designed to be inherently device independent.
However, there are specific physical features that an output device may
have that certain PostScript operators activate (in Level 1 implementations
many of these operators are found in statusdict). Examples of device-dependent
operators are legal, letter, and setsoftwareiomode. Use of these operators
can render a document device dependent; that is, the document images properly
on one type of device and not on others.<BR>
Use of DSC comments such as %%BeginFeature:, %%EndFeature (note that the
colon is part of the first comment and that this comment pair is often referred
to as %%Begin(End)Feature) and %%IncludeFeature: can help reduce device
dependency if a document manager is available to recognize these comments
and act upon them.<BR>
The DSC are designed to work with PostScript printer description (PPD) files,
which provide the PostScript language extensions for specific printer features
in a regular parsable format. PPD files include information about printer-specific
features, and include information about the fonts built into the ROM of
each printer. The DSC work in tandem with PPD files to provide a way to
specify and invoke these printer features in a device-independent manner.
For more information about PPD files, see the PostScript Printer Description
Files Specification available from the Adobe Systems Developers' Association.<BR>
Note Even though the DSC comments are a layer of communication beyond the
PostScript language and do not affect the final output, their use is considered
to be good PostScript language programming style.<BR>
<A NAME="usingDSC"></A><HR>
<A HREF="DSC.php">DSC Index</A><BR><p><p>

<HR><BR>
<BR>
<h2>1 Using the Document Structuring Conventions</h2><BR>
Ideally, a document composition system should be able to compose a document
regardless of available resources-for example, font availability and paper
sizes. It should be able to rely on the document management system at printing
time to determine the availability of the resources and give the user reasonable
alternatives if those resources are not available.<BR>
Realistically, an operating environment may or may not provide a document
management system. Consequently, the DSC contain some redundancy. There
are two philosophically distinct ways a resource or printer-specific feature
might be specified:<BR>
. The document composition system trusts its environment to handle the resource
and feature requirements appropriately, and merely specifies what its particular
requirements are.<BR>
. The document composer may not know what the network environment holds
or even that one exists, and includes the necessary resources and printer-specific
PostScript language instructions within the document. In creating such a
document, the document composer delimits these included resources or instructions
in such a way that a document manager can recognize and manipulate them.<BR>
It is up to the software developer to determine which of these methods is
appropriate for a given environment. In some cases, both may be used.<BR>
These two methods are mirrored in the DSC comments:<BR>
. Many DSC comments provide %%Begin and %%End constructs for identifying
resources and printer-specific elements of a document. The document then
prints regardless of whether a document manager is present or not.<BR>
. Many of the requirement conventions provide a mechanism to specify a need
for some resource or printer-specific feature through the use of %%Include
comments, and leave the inclusion of the resource or invocation of the feature
to the document manager. This is an example of complete network cooperation,
where a document can forestall some printing decisions and pass them to
the next layer of document management. In general, this latter approach
is the preferred one.<BR>
<A NAME="DMS"></A><HR><p><p>

<A HREF="DSC.php">DSC
Index</A><BR><p><p>

<HR><BR>
<BR>
<h2>2 Document Manager Services</h2><BR>
A document manager can provide a wide variety of services. The types of
services are grouped into five management categories: spool, resource, error,
print, and page management. The DSC help facilitate these services. A document
that conforms to this specification can expect to receive any of these services,
if available; one that does not conform may not receive any service. Listed
below are some of the services that belong to each of these categories.<BR>
<HR><A NAME="Spool"></A><HR> 
<h2>2.1 Spool Management</h2><BR>
Spooling management services are the most basic services that a document
manager can perform. A category of DSC comments known as general conventions-specifically
the header comments-provide information concerning the document's creator,
title, pages, and routing information.<BR>
<BR>
<A NAME="Spooling"></A><B>Spooling</B><BR>
The basic function of spool management is to deliver the document to the
specified printer or display. The document manager should establish queues
for each device to handle print job traffic in an effective manner, giving
many users access to one device. In addition, the document manager should
notify the user of device status (busy/idle, jammed, out of paper, waiting)
and queue status (held, waiting, printing). More advanced document managers
can offer job priorities and delayed-time printing.<BR>
<BR>
<A NAME="Banner"></A><B>Banner and Trailer Pages</B><BR>
As a part of spool management, a document manager can add a banner or trailer
page to the beginning or end, respectively, of each print job to separate
the output in the printer bin. The document manager can parse information
from the DSC comments to produce a proper banner that includes the title,
creator, creation date, the number of pages, and routing information of
the document.<BR>
<BR>
<A NAME="PrintLogging"></A><B>Print Logging</B><BR>
If a document manager tracks the number of pages, the type of media used,
and the job requirements for each document, the document manager can produce
a comprehensive report on a regular basis detailing paper and printer usage.
This can help a systems administrator plan paper purchases and estimate
printing costs. Individual reports for users can serve as a way to bill
internally for printing.<BR>
<BR>
<A NAME="Resource"></A>
<HR><p><p>

<A HREF="DSC.php">DSC
Index</A><BR><p><p>

<HR><BR>
<h2>2.2 Resource Management</h2><BR>
Resource management services deal with the inclusion, caching, and manipu-lation
of resources, such as fonts, forms, files, patterns, and documents. A category
of DSC comments, known as requirement conventions, enables a document manager
to properly identify instances in the document when resources are either
needed or supplied.<BR>
<BR>
Resource Inclusion<BR>
Frequently used resources, such as company logos, copyright notices, special
fonts, and standard forms, can take up vast amounts of storage space if
they are duplicated in a large number of documents. The DSC support special
%%Include comments so a document manager can include a resource at print
time, saving disk space.<BR>
<BR>
Supplied resources can be cached in a resource library for later use. For
example, a document manager that identifies a frequently used logo while
processing a page description subsequently stores the logo in a resource
library. The document manager then prints the document normally. When future
%%IncludeResource: comments are found in succeeding documents, the document
manager retrieves the PostScript language program for the logo from the
resource library. The program is inserted into the document at the position
indicated by the DSC comment before the document is sent to the printer.<BR>
<BR>
<A NAME="ResourceDownloading"></A><B>Resource Downloading</B><BR>
Another valuable service that a document manager can provide is automatically
downloading frequently used resources to specific printers so those resources
are available instantly. Transmission and print time of documents can be
greatly reduced by using this service.<BR>
<BR>
For example, the document manager judges that the Stone-Serif font program
is a frequently used resource. It downloads the font program from the resource
library to the printer. Later, the document manager receives a document
that requests the Stone-Serif font program. The document manager knows this
resource is already available in the printer and sends the document to the
printer without modification. Note that the resource can be downloaded persistently
into VM or onto a hard disk if the printer has one. For Level 2 interpreters,
resources are found automatically by the findresource operator.<BR>
<BR>
<A NAME="ResourceOpt"></A><B>Resource Optimization</B><BR>
An intelligent document manager can alter the position of included resources
within a document to optimize memory and/or resource usage. For example,
if an encapsulated PostScript (EPS) file is included several times in a
document, the document manager can move duplicate procedure set defini-tions
(procsets) to the top of the document to reduce transmission time. If a
document manager performs dynamic resource positioning, it must main-tain
the relative order of the resources to preserve any interdependencies among
them.<BR>
<BR>
<A NAME="Error"></A>
<HR><p><p>

<A HREF="DSC.php">DSC
Index</A><BR><p><p>

<HR><BR>
<h2>2.3 Error Management</h2><BR>
A document manager can provide advanced error reporting and recovery services.
By downloading a special error handler to the printer, the document manager
can detect failed print jobs and isolate error-producing lines of PostScript
language instructions. It can send this information, a descriptive error
message, and suggestions for solution back to the user.<BR>
There may be other instances where a document manager can recover from certain
types of errors. Resource substitution services can be offered to the user.
For example, if your document requests the Stone-Serif font program and
this font program is not available on the printer or in the resource library,
a document manager could select a similar font for substitution.<BR>
<BR>
<A NAME="Print"></A>
<HR><p><p>

<A HREF="DSC.php">DSC
Index</A><BR><p><p>

<HR><BR>
<h2>2.4 Print Management</h2><BR>
Good print management ensures that the requested printer can fulfill the
requirements of a particular document. This is a superset of the spool management
spooling function, which is concerned with delivering the print job to the
printer regardless of the consequences. By understanding the capabilities
of a device and the requirements of a document, a document manager can provide
a wide variety of print management services.<BR>
<BR>
<A NAME="PrinterRerouting"></A><B>Printer Rerouting</B><BR>
A document manager can reroute documents based on printer availability.
Heavily loaded printers can have their print jobs off-loaded to different
printers in the network. The document manager can also inform a user if
a printer is busy and suggest an idle printer for use as a backup.<BR>
<BR>
If a specified printer cannot meet the requirements of a document (if for
example, the document requests duplex printing and the printer does not
support this feature), the document manager can suggest alternate printers.<BR>
<BR>
For example, a user realizes that a document to be printed on a monochrome
printer contains a color page. The user informs the document manager that
the document should be rerouted to the color printer. Any printer-specific
portions are detected by the document manager via the %%Begin(End)Feature:
comments. The document manager consults the appropriate PostScript printer
description (PPD) file, the printer-specific portion is replaced in the
document, and the document is rerouted to the appropriate queue.<BR>
<BR>
<A NAME="Feature"></A><B>Feature Inclusion</B><BR>
<BR>
This service is similar in concept to resource inclusion. Instead of using
PostScript language instructions that activate certain features of a target
printer, an application can use the %%IncludeFeature: comment to specify
that a fragment of feature instructions should be included in the document
at a specific point. A document manager can recognize such a request, consult
the PPD file for the target printer, look for the specified feature, and
insert the code into the document before sending it to the printer.<BR>
<BR>
<A NAME="ParallelPrinting"></A><B>Parallel Printing</B><BR>
<BR>
Parallel printing, another possible feature of a document manager, is especially
useful for large documents or rush orders. Basically, the document manager
splits the document based on the %%Page: comment, sending different pieces
of the document to different printers simultaneously. The document is printed
in parallel.<BR>
<BR>
For example, a user requests that the first 100 pages of a document be printed
in parallel on five separate printers. The document manager splits the document
into five sections of 20 pages each, replicating the original prolog and
document setup for each section. Also, a banner page is specified for each
section to identify the pages being printed.<BR>
<BR>
<A NAME="PageBreakout"></A><B>Page Breakout</B><BR>
<BR>
Color and high-resolution printing are often expensive propositions. It
does not make sense to send an entire document to a color printer if the
document contains only one color illustration. When the appropriate comments
are used, document managers can detect color illustrations and detailed
drawings that need to be printed on high resolution printers, and split
them from the original document. The document manager sends these pages
separately to a high-resolution or color printer, while sending the rest
of the document to lower-cost monochrome printers.<BR>
<BR>
<A NAME="Page"></A>
<HR><p>
 
<A HREF="DSC.php">DSC
Index</A><BR>
<BR><p><p>

<HR><BR>
<BR>
<h2>2.5 Page Management</h2><BR>
<BR>
Page management deals with organizing and reorganizing individual pages
in the document. A category of comments known as page comments facilitate
these services. See section 4.5, &quot;Convention Categories,&quot; for
a thorough description of page-level comments.<BR>
<BR>
<A NAME="PageReversa"></A><B>Page Reversal</B><BR>
<BR>
Some printers place output in the tray face-up, some face-down. This small
distinction can be a nuisance to users who have to reshuffle output into
the correct order. Documents that come out of the printer into a face-up
tray should be printed last page first; conversely, documents that end up
face-down should be printed first page first. A document manager can reorder
pages within the document based on the %%Page: comment to produce either
of these effects.<BR>
<BR>
<A NAME="n-Up"></A><B>n-Up Printing</B><BR>
<BR>
n-up, thumbnail, and signature printing all fall under this category. This
enables the user to produce a document that has multiple virtual pages on
fewer physical pages. This is especially useful when proofing documents,
and requires less paper.<BR>
<BR>
For example, suppose a user wants a proof of the first four pages of a docu-ment
(two copies, because the user's manager is also interested). Two-up printing
is specified, where two virtual pages are mapped onto one physical sheet.
The document manager adds PostScript language instructions (usually to the
document setup section) that will implement this service.<BR>
<BR>
<A NAME="RangePrint"></A><B>Range Printing</B><BR>
<BR>
Range printing is useful when documents need not be printed in their entirety.
A document manager can isolate the desired pages from the document (using
the %%Page: comment and preserving the prolog and document setup) before
sending the new document to the printer. In the previous example, the user
may want only the first four pages of the document. The document manager
determines where the first four pages of the document reside and discards
the rest.<BR>
<BR>
<A NAME="CollatedPrint"></A><B>Collated Printing</B><BR>
<BR>
When using the#copies or setpagedevice features to specify multiple copies,
on some printers the pages of the document emerge uncollated (1-1-1-2-2-2-3-3-3).
Using the same mechanics as those for range printing, a document manager
can print a group of pages multiple times and obtain collated output (1-2-3-1-2-3-1-2-3),
saving the user the frustration of hand collating the document.<BR>
<BR>
Underlays<BR>
<BR>
Underlays are text and graphic elements, such as draft and confidential
notices, headers, and images, that a document manager can add to a document
so they appear on every page. By adding PostScript language instructions
to the document setup, each page of the document renders the underlay before
drawing the page itself.<BR>
<BR>
<A NAME="Conformance"></A>
<HR><p>
 
<A HREF="DSC.php">DSC
Index</A><BR>
<BR><p><p>

<HR><BR>
<BR>
<h2>3 DSC Conformance</h2><BR>
<BR>
The PostScript interpreter does not distinguish between PostScript language
page descriptions that do or do not conform to the DSC. However, the struc-tural
information communicated through DSC comments is of considerable importance
to document managers that operate on PostScript page descrip-tions as data.
Because document managers cannot usually interpret the PostScript language
directly, they must rely on the DSC comments to properly manipulate the
document. It is necessary to distinguish between those documents that conform
to the DSC and those that do not.<BR>
<BR>
Note In previous versions of the DSC, there were references to partially
conforming documents. This term has caused some confusion and its use has
been discontinued. A document either conforms to the conventions or it does
not.<BR>
<BR>
<HR><A NAME="ConformingDoc"></A>
<h2>3.1 Conforming Documents</h2><BR>
<BR>
A conforming document can expect to receive the maximum amount of services
from any document manager. A conforming document is recognized by the header
comment %!PS-Adobe-3.0 and is optionally followed by keywords indicating
the type of document. Please see the description of this comment in section
5, &quot;General Conventions,&quot; for more details about optional keywords.<BR>
<BR>
A fully conforming document is one that adheres to the following rules regarding
syntax and semantics, document structure, and the compliance constraints.
It is also strongly suggested that documents support certain printing services.<BR>
<BR>
Syntax and Semantics<BR>
<BR>
If a comment is to be used within a document, it must follow the syntactical
and semantic rules laid out in this specification for that comment.<BR>
<BR>
Consider the following incorrect example:<BR>
<BR>
<div class=code>
<CODE>%%BoundingBox 43.22 50.45 100.60 143.49</CODE><BR>
</div>
<BR>
This comment is incorrect on two counts. First, there is a colon missing
from the %%BoundingBox: comment. Abbreviations for comments are not accept-able.
Second, floating point arguments are used instead of the integer argu-ments
this comment requires.<BR>
<BR>
<A NAME="DocStructure"></A><B>Document Structure</B><BR>
<BR>
The document structure rules described in section 4, &quot;Document Structure
Rules,&quot; must be followed. The following comments delineate the structure
of the document. If there is a section of a document that corresponds to
a particular comment, that comment must be used to identify that section
of the document.<BR>
<BR>
<div class=code>
<CODE>%!PS-Adobe-3.0 <BR>
<BR>
%%Pages: <BR>
<BR>
%%EndComments <BR>
<BR>
%%BeginProlog <BR>
<BR>
%%EndProlog <BR>
<BR>
%%BeginSetup <BR>
<BR>
%%EndSetup <BR>
<BR>
%%Page: <BR>
<BR>
%%BeginPageSetup <BR>
<BR>
%%EndPageSetup <BR>
<BR>
%%PageTrailer <BR>
<BR>
%%Trailer <BR>
<BR>
%%EOF<BR>
<BR>
</CODE>
</div>
For example, if there are distinct independent pages in a document,
the %%Page: comment must be used at the beginning of each page to identify
those pages.<BR>
<BR>
Structure of a conforming PostScript language document<BR>
<BR>
<div class=code>
<CODE>%!PS-Adobe-3.0<BR>
<BR>
...DSC comments only...<BR>
<BR>
%%EndComments<BR>
<BR>
%%BeginProlog<BR>
<BR>
%%BeginResource: procset name 1<BR>
<BR>
...PostScript code and DSC comments...<BR>
<BR>
%%EndResource<BR>
<BR>
. .<BR>
<BR>
%%BeginResource: procset name n<BR>
<BR>
...PostScript code and DSC comments...<BR>
<BR>
%%EndResource<BR>
<BR>
%%EndProlog<BR>
<BR>
%%BeginSetup<BR>
<BR>
...PostScript code and DSC comments...<BR>
<BR>
%%EndSetup<BR>
<BR>
%%Page: label 1 ordinal 1<BR>
<BR>
...DSC comments only...<BR>
<BR>
%%BeginPageSetup<BR>
<BR>
...PostScript code and DSC comments...<BR>
<BR>
%%EndPageSetup<BR>
<BR>
...PostScript code and DSC comments...<BR>
<BR>
%%PageTrailer<BR>
<BR>
...PostScript code and DSC comments... . . .<BR>
<BR>
%%Page: label n ordinal n<BR>
<BR>
...DSC comments only...<BR>
<BR>
%%BeginPageSetup<BR>
<BR>
...PostScript code and DSC comments...<BR>
<BR>
%%EndPageSetup<BR>
<BR>
...PostScript code and DSC comments...<BR>
<BR>
%%PageTrailer<BR>
<BR>
...PostScript code and DSC comments...<BR>
<BR>
%%Trailer<BR>
<BR>
...PostScript code and DSC comments...</CODE><BR>
</div>
<BR>
Compliance Constraints<BR>
<BR>
The compliance constraints described in section 4.3, &quot;Constraints,&quot;
including the proper use of restricted operators, must be adhered to..<BR>
<BR>
Where sections of the structure are not applicable, those sections and their
associated comments need not appear in the document. For example, if a document
setup is not performed inside a particular document, the %%BeginSetup and
%%EndSetup comments are unnecessary. Figure 1 illustrates the structure
of a conforming PostScript language document.<BR>
<BR>
<A NAME="PrintingServices"></A><B>Printing Services</B><BR>
<BR>
There are document manager printing services (such as those described in
section 2, &quot;Document Manager Services&quot;) that can be easily supported
and add value to an application. Although it is not a requirement of a conforming
document, it is strongly suggested that applications support these services
by using the comments listed below. Note that 20 comments will ensure support
of all services.<BR>
<BR>
<A NAME="SpoolManagement"></A><B>Spool Management (Spooling, Banner and
Trailer Pages, and Print Logging) <BR>
<BR>
</B>
<div class=code>
<CODE>%%Creator: %%PageMedia: <BR>
<BR>
%%CreationDate: %%PageRequirements: <BR>
<BR>
%%DocumentMedia: %%Requirements: <BR>
<BR>
%%DocumentPrinterRequired: %%Routing: <BR>
<BR>
%%For: %%Title:</CODE><BR>
</div>
<BR>
<A NAME="ResourceManagement"></A><B>Resource Management (Resource Inclusion,
Downloading, and Optimization) <BR>
<BR>
</B>
<div class=code>
<CODE>%%DocumentNeededResources: %%IncludeResource: <BR>
<BR>
%%DocumentSuppliedResources: %%Begin(End)Resource: <BR>
<BR>
%%PageResources:</CODE><BR>
</div>
<BR>
<A NAME="ErrorManagement"></A><B>Error Management (Error Reporting and Recovery)
<BR>
<BR>
</B>
<div class=code>
<CODE>%%Extensions: %%ProofMode: %%LanguageLevel:</CODE><BR>
</div>
<BR>
<A NAME="PrinterManagement"></A><B>Printer Management (Printer Rerouting,
Feature Inclusion, Parallel Printing, Color Breakout) <BR>
<BR>
</B>
<div class=code>
<CODE>%%Begin(End)Feature: %%IncludeFeature: <BR>
<BR>
%%Begin(End)Resource: %%IncludeResource: <BR>
<BR>
%%DocumentMedia: %%LanguageLevel: <BR>
<BR>
%%DocumentNeededResources: %%PageMedia: <BR>
<BR>
%%DocumentPrinterRequired: %%PageRequirements: <BR>
<BR>
%%DocumentSuppliedResources: %%PageResources: <BR>
<BR>
%%Extensions: %%Requirements:</CODE><BR>
</div>
<BR>
<A NAME="PageManagement"></A><B>Page Management (Page Reversal, N-up Printing,
Range Printing, Collation, Underlays) <BR>
<BR>
</B>
<div class=code>
<CODE>%%Pages: %%Page: <BR>
<BR>
%%EndComments %%Begin(End)PageSetup <BR>
<BR>
%%Begin(End)Setup %%PageTrailer <BR>
<BR>
%%Begin(End)Prolog %%Trailer<BR>
<BR>
</CODE>
</div>
<A NAME="Non-Conforming"></A>
<HR><p><p>

<A HREF="DSC.php">DSC
Index</A><BR>
<BR><p><p>

<HR><BR>
<BR>
<h2>3.2 Non-Conforming Documents</h2><BR>
<BR>
A non-conforming document most likely will not receive any services from
a document manager, may not be able to be included into another document,
and may not be portable. In some cases, this may be appropriate; a PostScript
language program may require an organization that is incompatible with the
DSC. This is especially true of very sophisticated page descriptions composed
directly by a programmer.<BR>
<BR>
However, for page descriptions that applications generate automatically,
adherence to the structuring conventions is strongly recommended, simple
to achieve, and essential in achieving a transparent corporate printing
network.<BR>
<BR>
<I>A non-conforming document is recognized by the %! header comment. Under
no circumstances should a non-conforming document use the %!PS-Adobe-3.0
header comment.</I><BR>
<BR>
<A NAME="DSR"></A><A NAME="Prolog"></A>
<HR><p>
 
<A HREF="DSC.php">DSC
Index</A><BR>
<BR><p><p>

<HR><BR>
<BR>
<h2>4 Document Structure Rules</h2><BR>
<BR>
One of the most important levels of document structuring in the PostScript
language is the distinction between the document prolog and the document
script. The prolog is typically a set of procedure definitions appropriate
for the set of operations a document composition system needs, and the script
is the software-generated program that represents a particular document.<BR>
<BR>
A conforming PostScript language document description must have a clearly
defined prolog and script separated by the %%EndProlog comment.<BR>
<BR>
<HR><h2>4.1 Prolog</h2><BR>
<BR>
The prolog consists of a header section, an optional defaults subsection,
and the prolog proper, sometimes known as the procedures section.<BR>
<BR>
The header section consists of DSC comments only and describes the environment
that is necessary for the document to be output properly. The end of the
header section is denoted by the %%EndComments comment (see the note on
header comments in section 4.5, &quot;Convention Categories&quot;).<BR>
<BR>
The defaults section is an optional section that is used to save space in
the document and as an aid to the document manager. The beginning of this
section is denoted by the %%BeginDefaults comment. Only DSC page comments
should appear in the defaults section. Information on the page-level comments
that are applicable and examples of their use can be found in section 5.2,
&quot;General Body Comments&quot; under the definition of %%Begin(End)Defaults.
The end of the defaults section is indicated by the %%EndDefaults comment.<BR>
<BR>
The beginning of the procedures section is indicated by the %%BeginProlog
comment. This section is a series of procedure set (procset) definitions;
each procset is enclosed between a %%BeginResource: procset and %%EndResource
pair. Procsets are groups of definitions and routines appropriate for different
imaging requirements.<BR>
<BR>
The prolog has the following restrictions:<BR>
<BR>
. Executing the prolog should define procsets only. For example, these procsets
can consist of abbreviations, generic routines for drawing graphics objects,
and routines for managing text and images.<BR>
<BR>
. A document-producing application should almost always use the same prolog
for all of its documents, or at least the prolog should be drawn from a
pool of common procedure sets. The prolog should always be constructed in
a way that it can be removed from the document and downloaded only once
into the printer. All subsequent documents that are downloaded with this
prolog stripped out should still execute correctly.<BR>
<BR>
. No output can be produced while executing the prolog, no changes can be
made to the graphics state, and no marks should be made on the page.<BR>
<BR>
<A NAME="Script"></A>
<HR><p>
 
<A HREF="DSC.php">DSC
Index</A><BR>
<BR><p><p>

<HR><BR>
<BR>
<h2>4.2 Script</h2><BR>
<BR>
The document script consists of three sections: a document setup section,
page sections, and a document trailer.<BR>
<BR>
. The document setup section is denoted by the %%Begin(End)Setup comments.
The document setup should consist of procedure calls for invoking media
selections (for example, setting page size), running initialization routines
for procsets, downloading a font or other resource, or setting some aspect
of the graphics state. This section should appear after the %%EndProlog
comment, but before the first %%Page: comment.<BR>
<BR>
. The pages section of the script consists of 1 to n pages, each of which
should be functionally independent of the other pages. This means that each
page should be able to execute in any order and may be physically rearranged,
resulting in an identical document as long as the information within it
is the same, but with the physical pages ordered differently. A typical
example of this page reordering occurs during a page-reversal operation
performed by a document manager.<BR>
<BR>
The start of each page is denoted by the %%Page: comment and can also contain
a %%Begin(End)PageSetup section (analogous to the document setup section
on a page level), and an optional %%PageTrailer section (similar to the
document trailer). In any event, each page will contain between the setup
and the trailer sections the PostScript language program necessary to mark
that page.<BR>
<BR>
. The document trailer section is indicated by the %%Trailer comment. PostScript
language instructions in the trailer consists of calls to termination routines
of procedures and post-processing or cleanup instructions. In addition,
any header comments that were deferred using the (atend) notation will be
found here. See section 4.6, &quot;Comment Syntax Reference,&quot; for a
detailed description of (atend).<BR>
<BR>
There are generally few restrictions on the script. It can have definitions
like the prolog and it can also modify the graphics environment, draw marks
on the page, issue showpage, and so on. There are some PostScript language
operators that should be avoided or at least used with extreme caution.
A thorough discussion of these operators can be found in Appendix I of the
PostScript Language Reference Manual, Second Edition.<BR>
<BR>
The end of a document should be signified by the 
<A HREF="DSC2.php#EOF">%%EOF</A>
comment.<BR>
<BR>
<A NAME="Constraints"></A>
<HR><p>
 
<A HREF="DSC.php">DSC
Index</A><BR>
<BR><p><p>

<HR><BR>
<BR>
<h2>4.3 Constraints</h2><BR>
<BR>
There are several constraints on the use of PostScript language operators
in a conforming document. These constraints are detailed below and are not
only applicable to documents that conform to the DSC. Even a non-conforming
document is much more portable across different PostScript interpreters
if it observes these constraints.<BR>
<BR>
Page Independence<BR>
<BR>
Pages should not have any inter-dependencies. Each page may rely on certain
PostScript language operations defined in the document prolog or in the
document setup section, but it is not acceptable to have any graphics state
set in one page of a document on which another page in the same document
relies on. It is also risky to reimpose or rely on a state defined in the
docu-ment setup section; the graphics state should only be added to or modified,
not reimposed. See Appendix I of the PostScript Language Reference Manual,
Second Edition for more details on proper preservation of the graphics state
with operators like settransfer.<BR>
<BR>
Page independence enables a document manager to rearrange the document's
pages physically without affecting the execution of the document description.
Other benefits of page independence include the ability to print different
pages in parallel on more than one printer and to print ranges of pages.
Also, PostScript language previewers need page independence to enable viewing
the pages of a document in arbitrary order.<BR>
<BR>
For the most part, page independence can be achieved by placing a save-restore
pair around each page, as shown below:<BR>
<BR>
<div class=code>
<CODE>%!PS-Adobe-3.0 <BR>
<BR>
...Header comments, prolog definitions, document setup... <BR>
<BR>
%%Page: cover 1 <BR>
<BR>
%%BeginPageSetup /pgsave save def <BR>
<BR>
...PostScript language instructions to perform page setup... <BR>
<BR>
%%EndPageSetup <BR>
<BR>
...PostScript language instructions to mark page 1... <BR>
<BR>
pgsave restore showpage <BR>
<BR>
...Rest of the document... <BR>
<BR>
%%EOF</CODE><BR>
</div>
<BR>
The save-restore pair will also reclaim any non-global VM used during the
page marking (for example, text strings).<BR>
<BR>
Note If pages must have interdependencies, the %%PageOrder: Special comment
should be used. This ensures that a document manager will not attempt to
reorder the pages.<BR>
<BR>
<A NAME="LineLength"></A><B>Line Length</B><BR>
<BR>
To provide compatibility with a large body of existing application and document
manager software, a conforming PostScript language document description
does not have lines exceeding 255 characters, excluding line-termination
characters. The intent is to be able to read lines into a 255-character
buffer without overflow (Pascal strings are a common example of this sort
of buffer).<BR>
<BR>
The PostScript interpreter imposes no constraints as to where line breaks
occur, even in string bodies and hexadecimal bitmap representations. This
level of conformance should not pose a problem for software development.
Any document structuring comment that needs to be continued on another line
to avoid violating this guideline should use the %%+ notation to indicate
that a comment line is being continued (see %%+ in section 5.2, &quot;General
Body Comments&quot;).<BR>
<BR>
Line Endings<BR>
<BR>
Lines must be terminated with one of the following combinations of characters:
CR, LF, or CR LF. CR is the carriage-return character and LF is the line-feed
character (decimal ASCII 13 and 10, respectively).<BR>
<BR>
<B>Use of showpage</B><BR>
<BR>
To reduce the amount of VM used at any point, it is common practice to delimit
PostScript language instructions used for a particular page with a save-restore
pair. See the page-independence constraint for an example of save-restore
use.<BR>
<BR>
If the showpage operator is used in combination with save and restore, the
showpage should occur after the page-level restore operation. The motivation
for this is to redefine the showpage operator so it has side effects in
the printer VM, such as maintaining page counts for printing n-up copies
on one sheet of paper. If showpage is executed within the confines of a
page-level save-restore, attempts to redefine showpage to perform extra
operations will not work as intended. This also applies to the BeginPage
and EndPage parameters of the setpagedevice dictionary. The above discussion
also applies to gsave-grestore pairs.<BR>
<BR>
Document Copies<BR>
<BR>
In a conforming document, the number of copies must be modified in the doc-ument
setup section of the document (see %%BeginSetup and %%EndSetup). Changing
the number of copies within a single page automatically breaks the page
independence constraint. Also, using the copypage operator is not recommended
because doing so inhibits page independence. If multiple copies of a document
are desired, use the#copies key or the setpagedevice operator.<BR>
<BR>
In Level 1 implementations, the#copies key can be modified to produce multiple
copies of a document as follows:<BR>
<BR>
<div class=code>
<CODE>%!PS-Adobe-3.0 <BR>
<BR>
%%Pages: 23 <BR>
<BR>
%%Requirements: numcopies(3) collate <BR>
<BR>
%%EndComments <BR>
<BR>
...Prolog with procset definitions... <BR>
<BR>
%%EndProlog <BR>
<BR>
%%BeginSetup /#copies 3 def <BR>
<BR>
%%EndSetup <BR>
<BR>
...Rest of the Document (23 virtual pages)... <BR>
<BR>
%%EOF<BR>
<BR>
</CODE>
</div>
In Level 2 implementations, the number of copies of a document can
be set using the setpagedevice operator as follows:<BR>
<BR>
&lt;&lt; /NumCopies 3 &gt;&gt; setpagedevice<BR>
<BR>
The %%Pages: comment should not be modified if the number of copies is set,
as it represents the number of unique virtual pages in the document. However,
the %%Requirements: comment should have its numcopies option modified, and
the collate option set, if applicable.<BR>
<BR>
<A NAME="Restricted"></A><B>Restricted Operators</B><BR>
<BR>
There are several PostScript language operators intended for system-level
jobs that are not appropriate in the context of a page description program.
Also, there are operators that impose conditions on the graphics state directly
instead of modifying or concatenating to the existing graphics state. How-ever,
improper use of these operators may cause a document manager to process
a document incorrectly. The risks of using these operators involve either
rendering a document device dependent or unnecessarily inhibiting constructive
post-processing of document files for different printing needs- for example,
embedding one PostScript language document within another.<BR>
<BR>
In addition to all operators in statusdict and the operators in userdict
for establishing an imageable area, the following operators should be used
carefully, or not at all, in a PostScript language page description:<BR>
<BR>
<div class=code>
<CODE>banddevice framedevice quit <BR>
<BR>
setpagedevice clear grestoreall <BR>
<BR>
renderbands setscreen cleardictstack <BR>
<BR>
initclip setglobal setshared copypage <BR>
<BR>
initgraphics setgstate settransfer <BR>
<BR>
erasepage initmatrix sethalftone <BR>
<BR>
startjob exitserver nulldevice <BR>
<BR>
setmatrix undefinefont</CODE><BR>
</div>
<BR>
For more specific information on the proper use of these operators in various
situations, see Appendix I of the PostScript Language Reference Manual,
Second Edition.<BR>
<BR>
There are certain operators specific to the Display PostScript system that
are not part of the Level 1 and Level 2 implementations. These operators
are for display systems only and must not be used in a document. This is
a much more stringent restriction than the above list of restricted operators,
which may be used with extreme care. For a complete list see section A.1.2,
&quot;Display PostScript Operators, of the PostScript Language Reference
Manual, Second Edition.&quot;<BR>
<BR>
<A NAME="anchor560900"></A>
<HR><p>
 
<A HREF="DSC.php">DSC
Index</A><BR>
<BR><p><p>

<HR><BR>
<BR>
<A NAME="Parsing"></A><h2>4.4 Parsing Rules</h2><BR>
Here are a few explicit rules that can help a document manager parse
the DSC comments: 
<UL>
  <LI>In the interest of forward compatibility, any comments that are not
recognized by the parser should be ignored. Backward compatibility is sometimes
difficult, and it may be helpful to develop an &quot;upgrading parser&quot;
that will read in documents conforming to older versions of the DSC and
write out DSC version 3.0 conforming documents. 
  <LI>Many comments have a colon separating the comment keyword from its
arguments. This colon is not present in all comment keywords (for example,
%%EndProlog) and should be considered part of the keyword for parsing purposes.
It is not an optional character. 
  <LI>Comments with arguments (like 
<A HREF="DSC2.php#Page">%%Page</A>:)
should have a space separating the colon from the first argument. Due to
existing software, this space must be considered optional. 
  <LI>&quot;White space&quot; characters within comments may be either spaces
or tabs (decimal ASCII 32 and 9, respectively). 
  <LI>Comment keywords are case-sensitive, as are all of the arguments following
a comment keyword. 
  <LI>The character set for comment keywords is limited to printable ASCII
characters. The keywords only contain alphabetic characters and the :, !,
and ? characters. The arguments may include any character valid in the PostScript
language character set, especially where procedure names, font names, and
strings are represented. See the definition of the &lt; text&gt; elementary
type for the use of the  escape mechanism. 
  <LI>When looking for the %%Trailer comment (or any (atend) comments),
allow for nested documents. Observe %%BeginDocument: and %%EndDocument comments
as well as 
<A HREF="DSC2.php#BeginData">%%BeginData</A>: and 
<A HREF="DSC2.php#BeginData">%%EndData</A>.
  <LI>In the case of multiple header comments, the first comment encountered
is considered to be the truth. In the case of multiple trailer comments
(those comments that were deferred using the (atend) convention), the last
comment encountered is considered to be the truth. For example, if there
are two %%Requirements: comments in the header of a document, use the first
one encountered. 
  <LI>Header comments can be terminated explicitly by an instance of %%EndComments,
or implicitly by any line that does not begin with %X, where X is any printable
character except space, tab, or newline. 
  <LI>The order of some comments in the document is significant, but in
a section of the document they may appear in any order. For example, in
the header section, %%DocumentResources:, %%Title:, and %%Creator: may appear
in any order. 
  <LI>Lines must never exceed 255 characters, and line endings should follow
the line ending restrictions set forth in section 4.3, &quot;Constraints.&quot;
  <LI>If a document manager supports resource or feature inclusion, at print
time it should replace %%Include comments with the resource or feature requested.
This resource or feature code should be encapsulated in %%Begin and %%End
comments upon inclusion. If a document manager performs resource library
extraction, any resources that are removed, including their associated %%Begin
and %%End comments, should be replaced by equivalent %%Include comments.
</UL>
<A NAME="Convention"></A>
<HR><p>
 
<A HREF="DSC.php">DSC
Index</A><BR>
<BR><p><p>

<HR><BR>
<BR>
<A NAME="Categories"></A>
<h2>4.5 Convention Categories</h2><BR>
<BR>
The DSC comments are roughly divided into the followingsix categories of
conventions: 
<UL>
  <LI>General conventions 
  <LI>Requirement conventions 
  <LI>Color separation conventions 
  <LI>Query conventions 
  <LI>Open structuring conventions 
  <LI>Special conventions 
</UL>
Typically, some subsets of the general, requirement, and color separation
conventions are used consistently in a particular printing environment.
The DSC have been designed with maximum flexibility in mind and with a mini-mum
amount of interdependency between conventions. For example, one may use
only general conventions in an environment where the presence of a document
manager may not be guaranteed, or may use the requirement con-ventions on
a highly spooled network.<BR>
<BR>
General conventions delimit the various structural components of a PostScript
language page description, including its prolog, script, and trailer, and
where the page breaks fall, if there are any. The general convention comments
include document and page setup information, and they provide a markup convention
for noting the beginning and end of particular pieces of the page description
that might need to be identified for further use.<BR>
<BR>
Requirement conventions are comments that suggest document manager action.
These comments can be used to specify resources the document sup-plies or
needs. Document managers may make decisions based on resource frequency
(those that are frequently used) and load resources permanently into the
printer, download them before the job, or store them on a printer's hard
disk, thus reducing transmission time.<BR>
<BR>
Other requirement comments invoke or delimit printer-specific features and
requirements, such as paper colors and weights, collating order, and stapling.
The document manager can replace printer-specific PostScript language fragments
based on these comments when rerouting a print job to another printer, by
using information in the PostScript printer description (PPD) file for that
printer.<BR>
<BR>
Color separation conventions are used to complement the color extensions
to the PostScript language. Comments typically identify PostScript language
color separation segments in a page, note custom color ratios (RGB or CMYK),
and list document and page level color use.<BR>
<BR>
Query conventions delimit parts of a PostScript language program that query
the current state or characteristics of a printer, including the availability
of resources (for example, fonts, files, procsets), VM, and any printer-specific
features and enhancements. The type of program that uses this set of conven-tions
is usually interactive-that is, one that expects a response from the printer.
This implies that document managers should be able to send query jobs to
a printer, and route an answer back to the application that issued the query.
Query conventions should only be used in %!PS-Adobe-3.0 Query jobs.<BR>
<BR>
Open structuring conventions are user-defined conventions. Section 9, &quot;Open
Structuring Conventions,&quot; provides guidelines for creating these vendor-specific
comments.<BR>
<BR>
Special conventions include those comments that do not fall into the above
categories.<BR>
<BR>
The general, requirement, and color separation conventions can be further
broken down into three classes: header comments, body comments, and page
comments.<BR>
<BR>
<A NAME="HeaderComments"></A><B>Header Comments</B><BR>
<BR>
Header comments appear first in a document file, before any of the executable
PostScript language instructions and before the procedure definitions. They
may be thought of as a table of contents. In order to simplify a docu-ment
manager's job in parsing these header comments, there are two rules that
apply:<BR>
<BR>
. If there is more than one instance of a header comment in a document file,
the first one encountered takes precedence. This simplifies nesting documents
within one another without having to remove the header comments.<BR>
<BR>
. Header comments must be contiguous. That is, if a document manager comes
across a line that does not begin with %, the document manager may quit
parsing for header comments. The comments may also be ended explicitly with
the %%EndComments convention.<BR>
<BR>
All instances of lines beginning with %! after the first instance are ignored
by document managers, although to avoid confusion, this notation should
not appear twice within the block of header comments (see %%BeginDocument:
and %%EndDocument for examples of embedded documents).<BR>
<BR>
<A NAME="BodyComments"></A><B>Body Comments</B><BR>
<BR>
Body comments may appear anywhere in a document, except the header sec-tion.
They are designed to provide structural information about the organiza-tion
of the document file and should match any related information provided in
the header comments section. They generally consist of %%Begin and %%End
constructs to delimit specific components of the document file, such as
procsets, fonts, or emulation code, and %%Include comments that request
the document manager to take action when encountering the comment, such
as including a document, resource, or printer-specific fragment of code.<BR>
<BR>
<B>Page Comments</B><BR>
<BR>
Page comments are page-level structure comments. They should not span across
page boundaries (see the exception below). That is, a page comment applies
only to the page in which it appears. The beginning of a page should be
noted by the 
<A HREF="DSC2.php#Page">%%Page</A>: comment. The other page
comments are similar to their corresponding header comments (for example,<p><p>

<A HREF="DSC2.php#BoundingBox">%%BoundingBox</A>: vs. 
<A HREF="DSC2.php#PageBoundingBox">%%PageBoundingBox</A>:),
except for %%Begin or %%End comments that are more similar to body comments
in use (e.g., 
<A HREF="DSC2.php#BeginSetup">%%Begin(End)Setup</A> vs. %%Begin(End)PageSetup).<BR>
<BR>
Note Some page comments that are similar to header comments can be used
in the defaults section of the file to denote default requirements or media
for all pages. See the 
<A HREF="DSC2.php#BeginDefaults">%%Begin(End)Defaults</A>
comments for a more detailed explanation.<BR>
<BR><p><p>

<HR><p><p>

<A HREF="DSC.php">DSC Index </A><p><p>

<A HREF="DSC2.php">Go to next
DSC document</A>
</BODY></HTML>
