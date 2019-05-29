
:fun My_stack_init()
: let dct ={'a':[]}
: let dct['len'] =function("My_stack_len")
: let dct['push'] =function("My_stack_push")
: let dct['pop'] =function("My_stack_pop")
: let dct['top'] =function("My_stack_top")
: return dct
:endfun
:fun My_stack_fini(stack)
: unlet a:stack['a']
: unlet a:stack['len']
: unlet a:stack['push']
: unlet a:stack['pop']
: unlet a:stack['top']
:endfun
:fun Stack_len() dict
: return len(self.a)
:endfun
:fun Stack_push(x) dict
: call add(self.a, a:x)
:endfun
:fun Stack_pop() dict
: let x =self.a[-1]
: call remove(self.a, -1)
: return x
:endfun
:fun Stack_top() dict
: return self.a[-1]
:endfun

