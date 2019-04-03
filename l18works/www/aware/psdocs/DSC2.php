<?php require_once("psdocs.php"); ?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN">
<html>
<head>
<title> Document Structuring Conventions</title>
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
<H1>PostScript Language Document Structuring Conventions Specification 1</H1><p>

<A NAME="Syntax"></A><HR>
<h2>4.6 Comment Syntax Reference</h2><BR>
Before describing the DSC comments, it is prudent to specify the syntax
with which they are documented. This section introduces a syntax known as
Backus-Naur form (BNF) that helps eliminate syntactical ambiguities and
helps comprehend the comment definitions. A brief explanation of the BNF
operators is given in Table 1. The following section discusses elementary
types, which are used to specify the keywords and options of the DSC comments.<BR>
<BR>
Table 1 Explanation of BNF operators<BR>
BNF Operator Explanation<BR>
&lt; token&gt; This indicates a token item. This item may comprise other
tokens or it may be an elementary type (see below).<BR>
::= Literally means &quot;is defined as.&quot;<BR>
[ expression ] This indicates that the expression inside the brackets is
optional.<BR>
{
 <p>

 
 expression } The braces are used to group expressions or tokens into single
expressions. It is often used to denote parsing order (it turns the expression
inside the braces into a single token).<BR>
&lt; token&gt; ... The ellipsis indicates that one or more instances of
&lt; token&gt; can be specified.<BR>
| The | character literally means &quot;or&quot; and delimits alter-native
expressions.<BR>
<BR><A NAME="Elementary"></A>
<B>Elementary Types</B><BR>
An elementary or base type is a terminating expression. That is, it does
not reference any other tokens and is considered to be a base on which other
expressions are built. For the sake of clarity, these base types are defined
here in simple English, without the exhaustive dissection that BNF normally
requires.<BR>
(atend) Some of the header and page comments can be deferred until the end
of the file (that is, to the %%Trailer section) or to the end of a page
(that is, the %%PageTrailer section). This is for the benefit of application
programs that generate page descriptions on-the-fly. Such applications might
not have the necessary information about fonts, page count, and so on at
the beginning of generating a page description, but have them at the end.
If a particular com-ment is to be deferred, it must be listed in the header
section with an (atend) for its argument list. A comment with the same keyword
and its appropriate arguments must appear in the %%Trailer or %%PageTrailer
sections of the document.<BR>
The following comments support the (atend) convention:<BR>
<div class=code>
<CODE><BR>
%%BoundingBox: %%DocumentSuppliedProcSets: <BR>
%%DocumentCustomColors: %%DocumentSuppliedResources: <BR>
%%DocumentFiles: %%Orientation: %%DocumentFonts: <BR>
%%Pages: %%DocumentNeededFiles: %%PageBoundingBox: <BR>
%%DocumentNeededFonts: %%PageCustomColors: <BR>
%%DocumentNeededProcSets: %%PageFiles: <BR>
%%DocumentNeededResources: %%PageFonts: <BR>
%%DocumentProcSets: %%PageOrder: %%DocumentProcessColors: <BR>
%%PageOrientation: %%DocumentSuppliedFiles: <BR>
%%PageProcessColors: %%DocumentSuppliedFonts: <BR>
%%PageResources:</CODE><BR>
</div>
<BR>
Note Page-level comments specified in the defaults section of the document
cannot use the (atend) syntax to defer definition of their arguments. (atend)
can only be used in the header section and within individual pages.<BR>
<BR>
In Example 1, the bounding box information is deferred until the end of
the document:<BR>
Example 1<BR>
<BR>
<div class=code>
<CODE>%!PS-Adobe-3.0 <BR>
...Document header comments... <BR>
%%BoundingBox: (atend) <BR>
%%EndComments <BR>
...Rest of the document... <BR>
%%Trailer <BR>
%%BoundingBox: 0 0 157 233 <BR>
...Document clean up... <BR>
%%EOF<BR>
<BR><A NAME="filename"></A>
</CODE>
</div>
<B>&lt;filename&gt; </B><BR>
A filename is similar to the &lt; text&gt; elementary type in that it can
comprise any printable character. It is usually very operating system specific.
The following example comment lists four different files:<BR>
<BR>
<div class=code>
<CODE>%%DocumentNeededResources: file /usr/smith/myfile.epsf <BR>
%%+ file (Corporate Logo 042large size042) (This is (yet) another file)
<BR>
%%+ file C:LIBLOGO.EPS<BR>
</CODE><BR>
</div>
Note that the backslash escape mechanism is only supported inside parentheses.
It can also be very convenient to list files on separate lines using the
continuation comment %%+.<BR>
<BR>
<B>&lt;fontname&gt; </B><BR>
A fontname is a variation of the simple text string (see &lt; text&gt;).
Because font names cannot include blanks, font names are considered to be
delimited by blanks. In addition, the  escape mechanism is not supported.
The following example comment uses five font names:<BR>
%%DocumentNeededResources: font Times-Roman Palatino-Bold %%+ font Helvetica
Helvetica-Bold NewCenturySchoolbook-Italic<BR>
The font name does not start with a slash character (/) as it does in the
PostScript language when you are specifying the font name as a literal.<BR>
<BR><A NAME="formname"></A>
<B>&lt;formname&gt; </B><BR>
A formname is the PostScript language object name of the form as used by
the defineresource operator. It is a simple text string as defined by the
&lt; text&gt; elementary type.<BR>
<BR><A NAME="int"></A>
<B>&lt;int&gt; </B><BR>
An integer is a non-fractional number that may be signed or unsigned. There
are practical limitations for an integer's maximum and minimum values (see
Appendix B of the PostScript Language Reference Manual, Second Edition).<BR>
<BR><A NAME="procname"></A>
<B>&lt;procname&gt;</B>::= &lt; name&gt; &lt; version&gt; &lt; revision&gt;
<BR>
&lt; name&gt; ::= &lt; text&gt; <BR>
&lt; version&gt; ::= &lt; real&gt; <BR>
&lt; revision&gt; ::= &lt; uint&gt; <BR>
A procname token describes a procedure set (procset), which is a block of
PostScript language definitions. A procset is labeled by a text string describ-ing
its contents and a version number. A procset version may undergo several
revisions, which is indicated by the revision number. Procset names should
be descriptive and meaningful. It is also suggested that the corporate name
and application name be used as part of the procset name to reduce conflicts,
as in this example:<BR>
(MyCorp MyApp - Graphic Objects) 1.1 0 Adobe-Illustrator-Prolog 2.0 1<BR>
The name, version, and revision fields should uniquely identify the procset.
If a version numbering scheme is not used, these fields should still be
filled with a dummy value of 0.<BR>
The revision field should be taken to be upwardly compatible with procsets
of the same version number. That is, if myprocs 1.0 0 is requested, then
myprocs 1.0 2 should be compatible, although the converse (backward compatibility)
is not necessarily true. If the revision field is not present, a procset
may be substituted as long as the version numbers are equal. Different versions
of a procset may not be upwardly compatible and should not be substituted.<BR>
<BR><A NAME="patternname"></A>
<B>&lt;patternname&gt; </B><BR>
A patternname is the PostScript language object name of the pattern as used
by the defineresource operator. It is a simple text string as defined by
the &lt; text&gt; elementary type.<BR>
<BR><A NAME="real"></A>
<B>&lt;real&gt; </B><BR>
A real number is a fractional number that may be signed or unsigned. There
are practical limitations on the maximum size of a real (see Appendix B
of the PostScript Language Reference Manual, Second Edition). Real numbers
may or may not include a decimal point, and exponentiation using either
an 'E' or an 'e' is allowed. For example,<BR>
-.002 34.5 -3.62 123.6e10 1E-5 -1. 0.0<BR>
are all valid real numbers.<BR>
<BR><A NAME="resource"></A>
&lt;resource&gt;::= font &lt; fontname&gt; | file &lt; filename&gt; | <BR>
procset &lt; procname&gt; | pattern &lt; patternname&gt; | <BR>
form &lt; formname&gt; | encoding &lt; vectorname&gt; <BR>
&lt;resources&gt; ::= font &lt; fontname&gt; ... | file &lt; filename&gt;
... | <BR>
procset &lt; procname&gt; ... | pattern &lt; patternname&gt; ... | <BR>
form &lt; formname&gt; ... | encoding &lt; vectorname&gt; ... <BR>
A resource is a PostScript object, referenced by name, that may or may not
be available to the system at any given time. Times-Roman is the name of
a commonly available resource. The name of the resource should be the same
as the name of the PostScript object-in other words, the same name used
when using the defineresource operator.<BR>
Note Although files are not resources in the PostScript language sense,
they can be thought of as a resource when document managers are dealing
with them.<BR>
<BR><A NAME="text"></A>
<B>&lt;text&gt; </B><BR>
A text string comprises any printable characters and is usually considered
to be delimited by blanks. If blanks or special characters are desired inside
the text string, the entire string should be enclosed in parentheses. Document
managers parsing text strings should be prepared to handle multiple parenthe-ses.
Special characters can be denoted using the PostScript language string 
escape mechanism.<BR>
The following are examples of valid DSC text strings:<BR>
Thisisatextstring (This is a text string with spaces) (This is a text string
(with parentheses)) (This is a special character 262 using the \ mechanism)<BR>
It is a good idea to enclose numbers that should be treated as text strings
in parentheses to avoid confusion. For example, use (1040) instead of 1040.<BR>
The sequence () denotes an empty string.<BR>
Note that a text string must obey the 255 character line limit as set forth
in section 3, &quot;DSC Conformance.&quot;<BR><A NAME="textline"></A>
&lt;textline&gt; This is a modified version of the &lt; text&gt; elementary
type. If the first character encountered is a left parenthesis, it is equivalent
to a &lt; text&gt; string. If not, the token is considered to be the rest
of the characters on the line until end of line is reached (some combination
of the CR and LF characters).<BR>
<BR><A NAME="uint"></A>
<B>&lt;uint&gt;</B><BR>
An unsigned integer is a non-fractional number that has no sign. There are
practical limitations for an unsigned integer's maximum value, but as a
default it should be able to range between 0 and twice the largest integer
value given in Appendix B of the PostScript Language Reference Manual, Second
Edition.<BR>
<BR><A NAME="vectorname"></A>
<B>&lt;vectorname&gt; </B><BR>
A vectorname denotes the name of a particular encoding vector and is also
a simple text string. It should have the same name as the encoding vector
the PostScript language program uses. Examples of encoding vector names
are StandardEncoding and ISOLatin1Encoding.<BR>
<A NAME="Conventions"></A><HR>
<h2>5 General Conventions</h2><BR>
The general conventions are the most basic of all the comments. They impart
general information, such as the bounding box, language level, extension
usage, orientation, title of the document, and other basics. There are com-ments
that are used to impart structural information (end of header, setup, page
breaks, page setup, page trailer, trailer) that are the keys to abiding
by the document structure rules of 3, &quot;DSC Conformance.&quot; Other
general com-ments are used to identify special sections of the document,
including binary and emulation data, bitmap previews, and page level objects.<BR>
<A NAME="Header"></A><HR>
<h2>5.1 General Header Comments</h2><BR>
<BR>
%!PS-Adobe-3.0 </B>&lt;keyword&gt; <BR>
&lt; keyword&gt; ::= EPSF-3.0 | Query | ExitServer | Resource-&lt; restype&gt;
<BR>
&lt; restype&gt; ::= Font | File | ProcSet | Pattern | Form | Encoding<BR>
<BR>
This comment differs from the previous %!PS-Adobe-2.1 comment only in version
number. It indicates that the PostScript language page description fully
conforms to the DSC version 3.0. This comment must occur as the first line
of the PostScript language file.<BR>
There are four keywords that may follow the %!PS-Adobe-3.0 comment on the
same line. They flag the entire print job as a particular type of job so
document managers may immediately switch to some appropriate processing
mode. The following job types are recognized: 
<UL>
<LI>EPSF-The file is an Encapsulated PostScript file, which is primarily
a PostScript language file that produces an illustration. The EPS format
is designed to facilitate including these illustrations in other documents.
The exact format of an EPS file is described in the PostScript Document
Structuring Conventions Specifications available from the Adobe Systems
Devlopers' Association. 
<LI>Query-The entire job consists of PostScript language queries to a printer
from which replies are expected. A systems administrator or document manager
is likely to create a query job. See section 12.4, &quot;Query Conventions.&quot;
<LI>. ExitServer-This flags a job that executes the exitserver or startjob
operator to allow the contents of the job to persist within the printer
until it is powered off. Some document managers require this command to
handle these special jobs effectively. See the discussion of exitserver
under %%Begin(End)ExitServer. 
<LI>Resource-As a generalization of the idea of Level 2 resources, files
that are strictly resource definitions (fonts, procsets, files, patterns,
forms) should start with this comment and keyword. For example, a procset
resource should start with the %!PS-Adobe-3.0 Resource-ProcSet comment.<BR>
Fonts are resources, as well, but most fonts use one of two different header
comments: %!PS-AdobeFont-1.0 and %!FontType1-1.0. In the future, fonts conforming
to this specification should use the %!PS-Adobe-3.0 Resource-Font comment.
</UL>
<I>Note Document composition programs should not use these keywords when
pro-ducing a document intended for printing or display. Instead, they should
use only the %!PS-Adobe-3.0 comment. Illustration applications may use the
EPSF-3.0 keyword.<BR>
<BR><A NAME="BoundingBox"></A>
</I><B>%%BoundingBox: </B>{
 <p>

 
 &lt;llx&gt; &lt;lly&gt; &lt;urx&gt; &lt;ury&gt;
} | (atend) <BR>
&lt; llx&gt; ::= &lt; int&gt; (Lower left x coordinate) <BR>
&lt; lly&gt; ::= &lt; int&gt; (Lower left y coordinate) <BR>
&lt; urx&gt; ::= &lt; int&gt; (Upper right x coordinate) <BR>
&lt; ury&gt; ::= &lt; int&gt; (Upper right y coordinate)<BR>
<BR>
This comment specifies the bounding box that encloses all marks painted
on all pages of a document. That is, it must be a &quot;high water mark&quot;
in all directions for marks made on any page. The four arguments correspond
to the lower left ( llx, lly) and upper right corners ( urx, ury) of the
bounding box in the default user coordinate system (PostScript units). See
also the %%PageBoundingBox: comment.<BR>
<BR><A NAME="Copyright"></A>
<B>%%Copyright: </B>&lt; textline&gt;<BR>
This comment details any copyright information associated with the docu-ment
or resource.<BR>
<BR><A NAME="Creator"></A>
<B>%%Creator: </B>&lt; textline&gt;<BR>
This comment indicates the document creator, usually the name of the docu-ment
composition software.<BR>
<BR><A NAME="CreationDate"></A>
<B>%%CreationDate: </B>&lt; textline&gt;<BR>
This comment indicates the date and time the document was created. Neither
the date nor time need be in any standard format. This comment is meant
to be used purely for informational purposes, such as printing on banner
pages.<BR>
<BR><A NAME="DocumentData"></A>
<B>%%DocumentData: </B>Clean7Bit | Clean8Bit | Binary<BR>
This header comment specifies the type of data, usually located between
%%Begin(End)Data: comments, that appear in the document. It applies only
to data that are part of the document itself, not bytes that are added by
communications software-for example, an EOF character marking the end of
a job, or XON/XOFF characters for flow control. This comment warns a print
manager, such as a spooler, to avoid communications channels that reserve
the byte codes used in the document. A prime example of this is a serial
channel, which reserves byte codes like 0x04 for end of job and 0x14 for
status request.<BR>
There are three ranges of byte codes defined: 
<UL>
<LI>Clean7Bit-The page description consists of only byte codes 0x1B to 0x7E
(ESC to '~'), 0x0A (LF), 0x0D (CR), and 0x09 (TAB). Whenever 0x0A and/or
0x0D appear, they are used as end-of-line characters. Whenever 0x09 appears,
it is used as a tab character (i.e. whitespace). 
<LI>Clean8Bit-The same as Clean7Bit, but the document may also contain byte
codes 0x80-0xFF. 
<LI>Binary-Any byte codes from 0x00-0xFF may appear in the document. 
</UL>
The header section of the document (up to %%EndComments) must always consist
of Clean7bit byte codes so it is universally readable. If the application
declares the document to be Clean7Bit or Clean8Bit, it is responsible for
transforming any byte codes that fall outside the acceptable range back
into the acceptable range. Byte codes within character strings may be escaped-
for example, a 0x05 may be written (005).<BR>
Documents with Clean7Bit data may be transmitted to a PostScript interpreter
over a serial line with 7 data bits. Documents with Clean8Bit data may be
transmitted to a PostScript interpreter over a serial line with 8 data bits.
Documents with Binary data cannot be transmitted over a serial line because
they may use byte codes reserved by the communications protocol. However,
they may be transmitted via a transparent protocol, such as LocalTalk.<BR>
<BR><A NAME="Emulation"></A>
<B>%%Emulation:</B> &lt; mode&gt; ... &lt; mode&gt; ::= diablo630 | fx100
| lj2000 | hpgl | impress | hplj | ti855<BR>
This comment indicates that the document contains an invocation of the stated
emulator. This allows a document manager to route the document to a printer
that supports the correct type of emulation. See %%Begin(End)Emulation:
for more details.<BR>
<BR><A NAME="EndComments"></A>
<B>%%EndComments</B> (no keywords)<BR>
This comment indicates an explicit end to the header comments of the document.
Because header comments are contiguous, any line that does not begin with
%X where X is any printable character except space, tab, or newline implicitly
denotes the end of the header section.<BR>
<div class=code>
<CODE>%!PS-Adobe-3.0 <BR>
%%Title: (Example of Header Comment Termination) <BR>
...More header comments... <BR>
%%DocumentResources: font Sonata %GBDNodeName: <a href="mailto:smith@atlas.com">smith@atlas.com</a> <BR>
% This line implicitly denotes the end of the header section.<BR>
<BR><A NAME="Extensions"></A>
</CODE>
</div>
<B>%%Extensions:</B> &lt; extension&gt; ... &lt; extension&gt; ::=
DPS | CMYK | Composite | FileSystem<BR>
This comment indicates that in order to print properly, the document requires
a PostScript Level 1 interpreter that supports the listed PostScript language
extensions. The document manager can use this information to determine whether
a printer can print the document or to select possible printers for rerouting
the document. A list of operator sets specific to each extension is in Appendix
A of the PostScript Language Reference Manual, Second Edition. 
<UL>
<LI>DPS-The document contains operators defined in the PostScript language
extensions for the Display PostScript system. Most of these operators are
available in Level 2 implementations. See Appendix A of the PostScript Language
Reference Manual, Second Edition for a list of operators that are present
only in Display PostScript implementations. 
<LI>CMYK-The document uses operators defined in the PostScript language
color extensions. Note that this is different from the %%Requirements: color
comment, in that it specifies that the PostScript interpreter must be able
to understand the CMYK color operators. It does not specify that the printer
must be capable of producing color output. 
<LI>Composite-The document uses operators defined in the PostScript language
composite font extensions. 
<LI>FileSystem-This keyword should be used if the document performs file
system commands. Note that certain file operators are already available
under the basic implementation of the PostScript language. See Appendix
A of the PostScript Language Reference Manual, Second Edition for a list
of those operators that are specifically part of the file system extensions
to Level 1 implementations. 
</UL>
The %%Extensions: comment must be used if there are operators in the document
specific to a particular extension of the PostScript language. However,
documents that provide conditional Level 1 emulation do not need to use
this comment. Also, if the document uses Level 2 operators, use the %%LanguageLevel:
comment instead.<BR>
<BR><A NAME="For"></A>
<B>%%For:</B> &lt;textline&gt;<BR>
This comment indicates the person and possibly the company name for whom
the document is being printed. It is frequently the &quot;user name&quot;
of the individual who composed the document, as determined by the document
composition software. This can be used for banner pages or for routing the
document after printing.<BR>
<BR><A NAME="LanguageLevel"></A>
<B>%%LanguageLevel:</B> &lt; uint&gt;<BR>
This comment indicates that the document contains PostScript language operators
particular to a certain level of implementation of the PostScript language.
Currently, only Level 1 and Level 2 are defined.<BR>
This comment must be used if there are operators in the document specific
to an implementation of the PostScript language above Level 1. However,
documents that provide conditional Level 1 emulation (for example, Level
1 emulation of the Level 2 operators used) need not use this comment. See
Appendix D of the PostScript Language Reference Manual, Second Edition for
emulation and compatibility strategies.<BR>
Level 2 operators are essentially a superset of the DPS, CMYK, Composite,
and FileSystem language extensions. If a language level of 2 is specified,
the individual extensions need not be specified. That is, use of both the
%%LanguageLevel: and %%Extensions: comments is not necessary; one or the
other is sufficient. See the %%Extensions: comment.<BR>
Note To enable a document to be output to as many interpreters as possible,
a doc-ument composition application should determine the minimum set of
exten-sions needed for the document to print correctly. It is poor practice
to use the %%LanguageLevel: comment when an %%Extensions: comment would
have been able to encompass all of the operators used in the document.<BR>
<BR><A NAME="Orientation"></A>
<B>%%Orientation: </B>{
 <p>

 
 &lt; orientation&gt; ... } | (atend) &lt; orientation&gt;
::= Portrait | Landscape<BR>
This comment indicates the orientation of the pages in the document. It
can be used by previewing applications and post-processors to determine
how to orient the viewing window. A portrait orientation indicates that
the longest edge of the paper is parallel to the vertical (y) axis. A landscape
orientation indicates that the longest edge of the paper is parallel to
the horizontal (x) axis. If more than one orientation applies to the document,
an individual page should specify its orientation by using the %%PageOrientation:
comment.<BR>
<BR><A NAME="Pages"></A>
<B>%%Pages:</B> &lt; numpages&gt; | (atend) &lt; numpages&gt; ::= &lt; uint&gt;
(Total number of pages)<BR>
This comment defines the number of virtual pages that a document will image.
This may be different from the number of physical pages the printer prints
(the#copies key or setpagedevice operator and other document manager features
may reduce or increase the physical number of pages). If the document produces
no pages (for instance, if it represents an included illustration that does
not use showpage), the page count should be 0. See also the %%Page: comment.<BR>
In previous specifications, it was valid to include an optional page order
number after the number of pages. Its use is now discouraged because of
problems with the (atend) syntax (one might know the page order before one
knows the number of pages). Please use the %%PageOrder: comment to indicate
page order.<BR>
<BR><A NAME="PageOrder"></A>
<B>%%PageOrder:</B> &lt; order&gt; | (atend) &lt; order&gt; ::= Ascend |
Descend | Special<BR>
The %%PageOrder: comment is intended to help document managers determine
the order of pages in the document file, which in turn enables a document
manager optionally to reorder the pages. This comment can have three page
orders: 
<UL>
<LI>. Ascend-The pages are in ascending order-for example, 1-2-3-4-5. 
<LI>. Descend-The pages of the document are in descending order-for example,
5-4-3-2-1. 
<LI>. Special-Indicates that the document is in a special order-for example,
signature order. 
</UL>
The distinction between a page order of Special and no page order at all
is that in the absence of the %%PageOrder comment, any assumption can be
made about the page order, and the document manager permits any reorder-ing
of the page. However, if the page order comment is Special, the pages must
be left intact in the order given.<BR>
<BR><A NAME="Routing"></A>
<B>%%Routing:</B> &lt; textline&gt;<BR>
This comment provides information about how to route a document back to
its owner after printing. At the discretion of the system administrator,
it may contain information about mail addresses or office locations.<BR>
<BR><A NAME="Title"></A>
<B>%%Title: </B>&lt; textline&gt;<BR>
This comment provides a text title for the document that is useful for printing
banner pages and for routing or recognizing documents.<BR>
<BR><A NAME="Version"></A>
<B>%%Version:</B> &lt; version&gt; &lt; revision&gt; &lt; version&gt; ::=
&lt; real&gt; &lt; revision&gt; ::= &lt; uint&gt;<BR>
This comment can be used to note the version and revision number of a document
or resource. A document manager may wish to provide version control services,
or allow substitution of compatible versions/revisions of a resource or
document. Please see the &lt; procname&gt; elementary type for a more thorough
discussion of version and revisions.<BR>
<A NAME="Body"></A><HR>
<h2>5.2 General Body Comments</h2><BR>
<BR><A NAME="+"></A>
<B>%%+</B> (no keywords)<BR>
Any document structuring comment that must be continued on another line
to avoid violating the 255-character line length constraint must use the
%%+ notation to indicate that a comment line is being continued. This notation
may be used after any of the document comment conventions, but may only
be necessary in those comments that provide a large list of names, such
as %%DocumentResources:. Here is an example of its use:<BR>
<BR>
<div class=code><CODE>
%%DocumentResources: font Palatino-Roman Palatino-Bold <BR>
%%+ font Palatino-Italic Palatino-BoldItalic Courier <BR>
%%+ font Optima LubalinGraph-DemiOblique<BR>
</CODE></div>
See section 3, &quot;DSC Conformance,&quot; for more information about line
length and restrictions.<BR>
<BR><A NAME="BeginBinary"></A>
<B>%%BeginBinary:</B> &lt; bytecount&gt; &lt; bytecount&gt; ::= &lt; uint&gt;<BR>
<B>%%EndBinary</B> (no keywords)<BR>
These comments are used in a manner similar to the %%Begin(End)Data: comments.
The %%Begin(End)Binary: comments are designed to allow a document manager
to effectively ignore any binary data these comments encapsulate.<BR>
<BR>
To read data directly from the input stream in the PostScript language (using
currentfile, for instance), it is necessary to invoke a procedure followed
immediately by the data to be read. If the data is embedded in the %%Begin(End)Binary:
construct, those comments are effectively part of the data, which typically
is not desired. To avoid this problem, the procedure invocation should fall
inside the comments, even though it is not binary, and the bytecount should
reflect this so it can be skipped correctly. In the case of a byte count,
allow for carriage returns, if any.<BR>
<I>Note This comment has been included for backward compatibility only and
may be discontinued in future versions of the DSC; use the more specific
%%Begin(End)Data: comments instead.<BR>
<BR><A NAME="BeginData"></A>
</I><B>%%BeginData:</B> &lt; numberof&gt;[ &lt; type&gt; [ &lt; bytesorlines&gt;
] ] <BR>
&lt; numberof&gt; ::= &lt; uint&gt; (Lines or physical bytes) <BR>
&lt; type&gt; ::= Hex | Binary | ASCII (Type of data) <BR>
&lt; bytesorlines&gt; ::= Bytes | Lines (Read in bytes or lines)<BR>
<B>%%EndData</B> (no keywords)<BR>
These comments are designed to provide information about embedded bodies
of data. When a PostScript language document file is being parsed, encoun-tering
raw data can tremendously complicate the parsing process. Encapsu-lating
data within these comments can allow a document manager to ignore the enclosed
data, and speed the parsing process. If the type argument is missing, binary
data is assumed. If the bytesorlines argument is missing, numberof should
be considered to indicate bytes of data.<BR>
Note that &lt; numberof&gt; indicates the bytes of physical data, which
vary from the bytes of virtual data in some cases. With hex, each byte of
virtual data is represented by two ASCII characters (two bytes of physical
data). Although the PostScript interpreter ignores white space in hex data,
these count toward the byte count.<BR>
For example,<BR>
FD 10 2A 05<BR>
is 11 bytes of physical data (8 bytes hex, 3 spaces) and 4 binary bytes
of virtual data.<BR>
Remember that binary data is especially sensitive to different print environ-ments
because it is an 8-bit representation. This can be very important to the
document manager if a print network has a channel that is 7 bit serial,
for example. See also the %%DocumentData: comment.<BR>
<BR>
To read data directly from the input stream (using currentfile, for instance),
it is necessary to invoke a procedure followed immediately by the data to
be read. If the data is embedded in the %%Begin(End)Data: construct, then
those comments are effectively part of the data, which is typically not
desirable. To avoid this problem, the procedure invocation should fall inside
the com-ments, even though it is not binary, and the byte or line counts
should reflect this so it can be skipped correctly. In the case of a byte
count, allow for end-of-line characters, if any.<BR>
Note Document managers should ensure that the entire %%BeginData: comment
line is read before acting on the byte count.<BR>
In the example below, there are 135174 bytes of hex data, but the %%BeginData:
and %%EndData comments encompass the call to the image operator. The resulting
byte count includes 6 additional bytes, for the string &quot;image&quot;
plus the newline character.<BR>
<BR>
<div class=code>
<CODE>/picstr 256 string def <BR>
25 140 translate <BR>
132 132 scale <BR>
256 256 8 [256 0 0 -256 0 256] <BR>
{
 <p>

 
 currentfile picstr readhexstring pop } <BR>
%%BeginData: 135174 Hex Bytes image <BR>
4c47494b3187c237d237b137438374ab <BR>
213769876c8976985a5c987675875756 <BR>
...Additional 135102 bytes of hex... <BR>
%%EndData<BR>
</CODE><BR>
</div>
Instead of keeping track of byte counts, it is probably easier to keep track
of lines of data. In the following example, the line count is increased
by one to account for the &quot;image&quot; string:<BR>
<BR>
<div class=code>
<CODE>/picstr 256 string def <BR>
25 140 translate <BR>
132 132 scale <BR>
256 256 8 [256 0 0 -256 0 256] <BR>
{
 <p>

 
 currentfile picstr readhexstring pop } <BR>
%%BeginData: 4097 Hex Lines image <BR>
4c47494b3187c237d237b137438374ab <BR>
213769876c8976985a5c987675875756 <BR>
...Additional 4094 lines of hex... <BR>
%%EndData<BR>
</CODE><BR>
</div>
With binary data, it is unlikely that the concept of lines would be used,
because binary data is usually considered one whole stream.<BR>
<BR><A NAME="BeginDefaults"></A>
<B>%%BeginDefaults</B> (no keywords)<BR>
<B>%%EndDefaults</B> (no keywords)<BR>
These comments identify the start and end of the defaults section of the
document. These comments can only occur after the header section (%%EndComments),
after the EPSI preview (%%Begin(End)Preview), if there is one, but before
the prolog (%%BeginProlog) definitions.<BR>
Some page level comments that are similar to header comments can be used
in this defaults section of the file to denote default requirements, resources,
or media for all pages. This saves space in large documents (page-level
values do not need to be repeated for every page) and can give the document
man-ager some hints on how it might optimize resource usage in the file.
The only comments that can be used this way are the following:<BR>
<BR>
%%PageBoundingBox: <BR>
%%PageCustomColors: <BR>
%%PageMedia: <BR>
%%PageOrientation: <BR>
%%PageProcessColors: <BR>
%%PageRequirements: <BR>
%%PageResources:<BR>
<BR>
For example, if the %%PageOrientation: Portrait comment were used in the
defaults section, it would indicate that the default orientation for all
pages is portrait. When page-level comments are used this way they are known
as page defaults. Page comments used in a page override any page defaults
in effect. In reference to the previous example, if a particular page of
the document were to have a landscape orientation, it would place a %%PageOrientation:
Landscape comment after the %%Page: comment to override the default portrait
orientation.<BR>
<BR>
Example 2 illustrates the page default concept.<BR>
Example 2<BR>
<BR>
<div class=code><CODE>
%!PS-Adobe-3.0 <BR>
%%Title: (Example of page defaults) <BR>
%%DocumentNeededResources: font Palatino-Roman Helvetica <BR>
%%DocumentMedia: BuffLetter 612 792 75 buff ( ) <BR>
%%+ BlueLetter 612 792 244 blue (CorpLogo) <BR>
%%EndComments %%BeginDefaults <BR>
%%PageResources: font Palatino-Roman <BR>
%%PageMedia: BuffLetter <BR>
%%EndDefaults <BR>
%%BeginProlog <BR>
<i>...Prolog definitions...</i> <BR>
%%EndProlog <BR>
%%BeginSetup <BR>
<i>...PostScript language instructions to set the default paper size, weights,
and color...</i> <BR>
%%EndSetup <BR>
%%Page: Cover 1 <BR>
%%PageMedia: BlueLetter <BR>
%%BeginPageSetup <BR>
<i>...PostScript language instructions to set the blue corporate logo cover
paper...</i><BR>
%%EndPageSetup <BR>
<i>...Rest of page 1...</i> <BR>
%Page: ii 2 <BR>
%%PageResources: font Palatino-Roman Helvetica <BR>
<i>...Rest of page 2...</i> <BR>
%%Page: iii 3 <BR>
<i>...Rest of the document...</i> <BR>
%%EOF<BR>
</CODE></div>
<BR>
In this example, the font resource Palatino-Roman is specified in the defaults
section as a page resource. This indicates that Palatino-Roman is a page
default and will most likely be used on every page. Also, the media BuffLetter
is specified as the page default. Buff-colored, 20-lb, 8.5&quot; x 11&quot;
paper will be used for most pages.<BR>
Page 1 uses a special blue cover paper and overrides the page default (buff
paper) by putting a %%PageMedia: comment in the page definition. Page 2
uses buff paper and therefore doesn't have to put the %%PageMedia: comment
in its page definition. However, it does use the Helvetica font in addition
to the Palatino-Roman font. The page default of Palatino-Roman is overridden
by the %%PageResources: comment in the page definition.<BR>
<BR>
<I>Note In some instances it may be superfluous to use these page defaults.
If only one type of orientation, media type, etc. is used in the entire
document, the header comment alone is sufficient to indicate the default
for the document. Page defaults should only be used if there is more than
one bounding box, custom color, medium, orientation, process color, requirement,
or resource used.<BR>
<BR><A NAME="BeginEmulation"></A>
</I><B>%%BeginEmulation:</B> &lt; mode&gt; &lt; mode&gt; ::= diablo630 |
fx100 | lj2000 | hpgl | hplj | impress | ti855<BR>
<B>%%EndEmulation</B> (no keyword)<BR>
The %%BeginEmulation: comment signifies that the input data following the
comment contains some printer language other than PostScript. The first
line after the %%BeginEmulation comment should be the PostScript language
instructions to invoke the emulator. This code is in the PPD file for the
printer. Note that the invocation of the emulator is restricted to one line.<BR>
This comment enables a document manager to route the document or piece of
the document to an appropriate printer. The %%EndEmulation comment should
be preceded by the code to switch back to PostScript mode on printers that
support this type of switching (again, limit this code to one line). Alter-natively,
the %%EndEmulation comment may be omitted, in which case the end-of-file
switches the printer back into PostScript mode. The following example illustrates
the first approach:<BR>
<div class=code>
<CODE><BR>
%!PS-Adobe-3.0 <BR>
%%Title: (Example of emulator comments) <BR>
%%Emulation: hplj <BR>
%%EndComments <BR>
<i>...Prolog definitions and document setup...</i> <BR>
%%BeginEmulation: hplj 3 setsoftwareiomode <BR>
% Invoke hplj emulation <BR>
<i>...Emulator data...</i><BR>
1B 7F 30 <BR>
% Switch back to PostScript <BR>
%%EndEmulation <BR>
<i>...Remainder of document...</i><BR>
</CODE><BR>
</div>
Note When including emulator data, this may break the page independence
con-straint for a conforming PostScript language file, because there is
no way to signify page boundaries. Care should be taken when invoking specialized
features of the document manager, such as n-up printing. The document may
not be printed as expected.<BR>
<BR><A NAME="BeginPreview"></A>
<B>%%BeginPreview: </B>&lt; width&gt; &lt; height&gt; &lt; depth&gt; &lt;
lines&gt; <BR>
&lt; width&gt; ::= &lt; uint&gt; (Width of the preview in pixels) <BR>
&lt; height&gt; ::= &lt; uint&gt; (Height of the preview in pixels) <BR>
&lt; depth&gt; ::= &lt; uint&gt; (Number of bits of data per pixel) <BR>
&lt; lines&gt; ::= &lt; uint&gt; (Number of lines in the preview)<BR>
<B>%%EndPreview </B>(no keywords)<BR>
<BR>
These comments bracket the preview section of an EPS file in interchange
format (EPSI). The EPSI format is preferred over other platform-dependent
previews (for example, Apple Macintosh and IBM PC) when transferring EPS
files between heterogenous platforms. The width and height fields pro-vide
the number of image samples (pixels) for the preview. The depth field indicates
how many bits of data are used to establish one sample pixel of the preview
(typical values are 1, 2, 4, or 8). The lines field indicates how many lines
of hexadecimal data are contained in the preview, so that an application
disinterested in the preview can easily skip it.<BR>
The preview consists of a bitmap image of the file, as it would be rendered
on the page by the printer or PostScript language previewer. Applications
that use the EPSI file can use the preview image for on-screen display.
Each line of hexadecimal data should begin with a single percent sign. This
makes the entire preview section a PostScript language comment so the file
can be sent directly to a printer without modification. See section 6, &quot;Device-Indepen-dent
Screen Preview,&quot; of the Encapsulated PostScript Specifications avail-able
from the Adobe Systems Developers' Association.<BR>
The EPSI preview should be placed after the %%EndComments in the docu-ment
file, but before the defaults section (%%Begin(End)Defaults), if there is
one, and before the prolog (%%BeginProlog) definitions.<BR>
Note Preview comments can be used only in documents that comply with the
EPS file format. See the Encapsulated Postscript Specifications available
from the Adobe Systems Developers' Association for more details, including
platform-specific versions of the preview (Apple Macintosh and IBM PC platforms).<BR>
<BR><A NAME="BeginProlog"></A>
<B>%%BeginProlog </B>(no keywords)<BR>
<B>%%EndProlog </B>(no keywords)<BR>
These comments delimit the beginning and ending of the prolog in the docu-ment.
The prolog must consist only of procset definitions. The %%EndProlog comment
is widely used and parsed for, and must be included in all docu-ments that
have a distinct prolog and script.<BR>
<BR>
Breaking a document into a prolog and a script is conceptually important,
although not all document descriptions fall neatly into this model. If your
document represents free form PostScript language fragments that might entirely
be considered a script, you should still include the %%EndProlog comment,
even though there may be nothing in the prolog part of the file. This effectively
makes the entire document a script.<BR>
See section 3.1, &quot;Conforming Documents,&quot; and 4, &quot;Document
Structure Rules,&quot; for more information on the contents of the document
prolog.<BR>
<BR><A NAME="BeginSetup"></A>
<B>%%BeginSetup </B>(no keywords)<BR>
<B>%%EndSetup</B> (no keywords)<BR>
These comments delimit the part of the document that does device setup for
a particular printer or document. There may be instructions for setting
page size, invoking manual feed, establishing a scale factor (or &quot;landscape&quot;
mode), downloading a font, or other document-level setup. Expect to see
liberal use of the setpagedevice operator and statusdict operators between
these two comments. There may also be some general initialization instruc-tions,
such as setting some aspects of the graphics state. This code should be
limited to setting those items of the graphics state, such as the current
font, transfer function, or halftone screen, that will not be affected by
initgraphics or erasepage (showpage performs these two operations implicitly).
Special care must be taken to ensure that the document setup code modifies
the cur-rent graphics state and does not replace it. See Appendix I of the
PostScript Language Reference Manual, Second Edition for more information
about how to properly modify the graphics state.<BR>
If present, these comments appear after the %%EndProlog comment, but before
the first %%Page: comment. In other words, these comments are not part of
the prolog. They should be in the first part of the script before any pages
are specified.<BR>
<A NAME="GeneralPage"></A><HR>
<h2>5.3 General Page Comments</h2><BR>
Some of the following general page comments that specify the bounding box
or orientation may appear in the defaults section or in a particular page.
If these comments appear in the defaults section of the document file between
%%BeginDefaults and %%EndDefaults, they are in effect for the entire print
job. If they are found in the page-level comments for a page, they should
be in effect only for that page. See %%Begin(End)Defaults for more details
on page defaults.<BR>
<BR><A NAME="BeginObject"></A>
<B>%%BeginObject: </B>&lt;name&gt; [ &lt;code&gt; ] &lt; name&gt; ::= &lt;
text&gt; (Name of object) &lt; code&gt; ::= &lt; text&gt; (Processing code)<BR>
<B>%%EndObject</B> (no keywords)<BR>
These comments delimit individual graphic elements of a page. In a context
where it is desirable to be able to recognize individual page elements,
this comment provides a mechanism to label and recognize them at the PostScript
language level. Labelling is especially useful when a document printing
system can print selected objects in a document or on a page.<BR>
For instance, the code field of this comment can be used to represent proofing
levels for a document. For example, the printing manager may be requested
to &quot;print only those objects with proofing levels less than 4.&quot;
This can save printing time when proofing various elements of a document.
It can also be useful in systems that allow PostScript language program
segments to be parsed and re-edited into convenient groupings and categorizations
of graphic page elements. In a document production system or in an application
that is highly object-oriented, use of this comment is strongly recommended.<BR>
The user must specify to the application what things constitute an object
and what the proofing level of each object will be.<BR>
<BR><A NAME="BeginPageSetup"></A>
<B>%%BeginPageSetup</B> (no keywords)<BR>
<B>%%EndPageSetup</B> (no keywords)<BR>
These comments are analogous to the %%BeginSetup: and %%EndSetup comments,
except that %%BeginPageSetup: and %%EndPageSetup appear in the body of a
document right after a %%Page: comment. They delimit areas that set manual
feed, establish margins, set orientation, download fonts or other resources
for the page, invoke particular paper colors, and so on. This is the proper
place to set up the graphics state for the page. It should be assumed that
an initgraphics and an erasepage (i.e. showpage) have been performed prior
to this page. Take special care to ensure that the code in the page setup
modifies the current graphics state rather than replaces it. See Appendix
I of the PostScript Language Reference Manual, Second Edition for more information
about how to properly modify the graphics state.<BR>
<BR><A NAME="Page"></A>
<B>%%Page: </B>&lt;label&gt; &lt;ordinal&gt; <BR>
&lt; label&gt; ::= &lt; text&gt; (Page name) <BR>
&lt; ordinal&gt; ::= &lt; uint&gt; (Page position)<BR>
This comment marks the beginning of the PostScript language instructions
that describe a particular page. %%Page: requires two arguments: a page
label and a sequential page number. The label may be anything, but the ordinal
page number must reflect the position of that page in the body of the PostScript
language file and must start with 1, not 0. In the following example, the
name of the third page of the document is 1:<BR>
<BR>
<div class=code>
<CODE>%!PS-Adobe-3.0 <BR>
...Document prolog and setup... <BR>
%%Page: cover 1 <BR>
...Rest of the cover page... <BR>
%%Page: ii 2 <BR>
...Rest of the ii page... <BR>
%%Page: 1 3 <BR>
...Rest of the first page... <BR>
%%Page: 2 4 <BR>
...Rest of the second page... <BR>
%%EOF<BR>
</CODE><BR>
</div>
A document manager should be able to rearrange the contents of the print
file into a different order based on the %%Page: comment (or the pages may
be printed in parallel, if desired). The %%PageOrder: Special comment can
be used to inform a document manager that page reordering should not take
place.<BR>
<BR><A NAME="PageBoundingBox"></A>
<B>%%PageBoundingBox: </B>{
 <p>

 
 &lt;llx&gt; &lt;lly&gt; &lt;urx&gt; &lt;ury&gt;
} | (atend) <BR>
&lt; llx&gt; ::= &lt; int&gt; (Lower-left x coordinate) <BR>
&lt; lly&gt; ::= &lt; int&gt; (Lower-left y coordinate) <BR>
&lt; urx&gt; ::= &lt; int&gt; (Upper-right x coordinate) <BR>
&lt; ury&gt; ::= &lt; int&gt; (Upper-right y coordinate)<BR>
<BR>
This comment specifies the bounding box that encloses all the marks painted
on a particular page (this is not the bounding box of the whole document-
see the %%BoundingBox: comment). llx, llyand urx, ury are the coordinates
of the lower-left and upper-right corners of the bounding box in the default
user coordinate system (PostScript units). This comment can pertain to an
individual page or a document, depending on the location of the comment.
For example, the comment may be in the page itself or in the document defaults
section.<BR>
<BR><A NAME="PageOrientation"></A>
<B>%%PageOrientation: </B>Portrait | Landscape<BR>
This comment indicates the orientation of the page and can be used by preview
applications and post-processors to determine how to orient the viewing
window. This comment can pertain to an individual page or a docu-ment, depending
on the location of the comment. For example, the comment may be in the page
itself or in the document defaults section. See %%Orientation: for a description
of the various orientations. See %%Begin(End)Defaults for use of this comment
as a page default.<BR>
<A NAME="GeneralTrailer"></A><HR>
<h2>5.4 General Trailer Comments</h2><BR>
Some trailer comments are special and work with other comments that support
the (atend) notation. In addition, trailer comments delimit sections of
PostScript language instructions that deal with cleanup and other housekeep-ing.
This cleanup can affect a particular page or the document as a whole.<BR>
<BR><A NAME="PageTrailer"></A>
<B>%%PageTrailer </B>(no keywords)<BR>
This comment marks the end of a page. Any page comments that may have been
deferred by the (atend) convention should follow the %%PageTrailer comment.<BR>
<BR><A NAME="Trailer"></A>
<B>%%Trailer</B> (no keywords)<BR>
This comment must only occur once at the end of the document script. Any
post-processing or cleanup should be contained in the trailer of the docu-ment,
which is anything that follows the %%Trailer comment. Any of the document-level
structure comments that were deferred by using the (atend) convention must
be mentioned in the trailer of the document after the %%Trailer comment.<BR>
When entire documents are embedded in another document file, there may be
more than one %%Trailer comment as a result. To avoid ambiguity, embedded
documents must be delimited by the %%BeginDocument: and %%EndDocument comments.<BR>
<BR><A NAME="EOF"></A>
<B>%%EOF</B> (no keywords)<BR>
This comment signifies the end of the document. When the document manager
sees this comment, it issues an end-of-file signal to the PostScript interpreter.
This is done so system-dependent file endings, such as Control-D and end-of-file
packets, do not confuse the PostScript interpreter.<BR>
<BR><p>

<HR><p>
<A HREF="DSC.php">DSC Index </A><P>
<A HREF="DSC3.php">Go to next DSC document</A>
</BODY></HTML>
