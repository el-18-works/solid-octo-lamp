"
"
"  concatenatio
"
"
:source! vim/stack.vim

:fun My_tracepyfile_init()
: let s =My_stack_init()
: let s["error"] =function("My_tracepyfile_error")
: let s["call"] =function("My_tracepyfile_call")
:endfun
:fun My_tracepyfile_error(msg) dict
: echo msg
: exit
:endfun
:fun My_tracepyfile_call_resettabs(l) 
:  for i in range(strlen(l)) 
:    if l[i] =~ '\s'
:      return [l[:i], l[i:]]
:    en
:  endfo
:  return [l,""]
:endfun
:fun My_tracepyfile_call(filename, cb) dict
: let bn =bufnr(filename, 1) " create
: let ls =getbufline(bn, 1, "$")
: for i in range(len(ls))
:   let l =ls[i]
:   if i == 0 
:        self.push('')
:        cb("openfile", "")
:        cb("open", substitute(l, substitute(l, '^\s*', "", ""), '\s*$', "", ""))
:        continue
:   en
:   let [tabs,line] =resettabs(substitute(l, '\s*$', "", ""))
:   if not len(line) || line[0] == '#' && tabs != self.top() 
:     cb("comment", line)
:   else
:     while len(tabs) < len(self.top()) 
:       if self.top()[:len(tabs)] != tabs 
:         self.error("%d : '%s' : (close) inconsistent shift spaces/tabs"%(i+1, l))
:       en
:       self.pop()
:       cb("close", "")
:     endw
:     if tabs != self.top() 
:       if len(tabs) > len(self.top())
:         if tabs[:len(self.top())] != self.top()
:           self.error("%d : '%s' : (open) inconsistent shift spaces/tabs"%(i+1, l))
:         en
:         self.push(tabs)
:         cb("open", line)
:       else 
:         self.error("%d : '%s' : inconsistent shift spaces/tabs"%(i+1, l))
:       en
:     else 
:       cb("line", line)
:     en
:   en
: endfo
:    while self.len()
:      self.pop()
:      cb("close", "")
:    cb("closefile", "")
:endfun

:call s.push("1 foo")
:call s.push("2bar ")
:echo s.len()
:call append(line("$"), "test")
:call append(line("$"), s.pop())
:echo s.pop()
:call My_stack_fini(s)
:!echo foo
