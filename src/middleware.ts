import { createMiddlewareClient } from '@supabase/auth-helpers-nextjs';
import { NextResponse } from 'next/server';
import type { NextRequest } from 'next/server';

export async function middleware(req: NextRequest) {
  const res = NextResponse.next();
  const supabase = createMiddlewareClient({ req, res });
  
  const {
    data: { session },
  } = await supabase.auth.getSession();

  // Get the user's role
  let role = null;
  if (session) {
    const { data: profileData } = await supabase
      .from('profiles')
      .select('role')
      .eq('id', session.user.id)
      .single();
    
    role = profileData?.role;
  }

  // Check auth status
  const isLoggedIn = !!session;
  const isAdmin = role === 'admin';
  const isWorker = role === 'worker';
  
  // Get the path the user is trying to access
  const path = req.nextUrl.pathname;

  // Redirect logic based on authentication and role
  if (!isLoggedIn && path !== '/login') {
    return NextResponse.redirect(new URL('/login', req.url));
  }

  if (isLoggedIn && path === '/login') {
    if (isAdmin) {
      return NextResponse.redirect(new URL('/admin/dashboard', req.url));
    } else if (isWorker) {
      return NextResponse.redirect(new URL('/worker/dashboard', req.url));
    }
  }

  if (isWorker && path.startsWith('/admin')) {
    return NextResponse.redirect(new URL('/worker/dashboard', req.url));
  }

  return res;
}

export const config = {
  matcher: [
    /*
     * Match all request paths except:
     * - _next/static (static files)
     * - _next/image (image optimization files)
     * - favicon.ico (favicon file)
     * - public folder
     * - public files
     */
    '/((?!_next/static|_next/image|favicon.ico|.*\\.(?:svg|png|jpg|jpeg|gif|webp)$).*)',
  ],
};