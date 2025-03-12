import { createClient } from '@/lib/supabase/server';
import { redirect } from 'next/navigation';
import Link from 'next/link';

export default async function WorkerLayout({
  children,
}: {
  children: React.ReactNode
}) {
  const supabase = createClient();
  
  const { data: { session } } = await supabase.auth.getSession();
  
  if (!session) {
    redirect('/login');
  }
  
  const { data: profile } = await supabase
    .from('profiles')
    .select('*')
    .eq('id', session.user.id)
    .single();
    
  if (profile?.role !== 'worker') {
    redirect('/admin/dashboard');
  }
  
  return (
    <div>
      <header className="bg-indigo-600 text-white shadow">
        <div className="container mx-auto px-4 py-4 flex items-center justify-between">
          <div>
            <h1 className="text-xl font-bold">Barbershop App</h1>
          </div>
          
          <nav>
            <ul className="flex space-x-6">
              <li>
                <Link href="/worker/dashboard" className="hover:text-indigo-200">
                  Dashboard
                </Link>
              </li>
              <li>
                <Link href="/worker/services" className="hover:text-indigo-200">
                  Services
                </Link>
              </li>
              <li>
                <Link href="/worker/qrcode" className="hover:text-indigo-200">
                  QR Codes
                </Link>
              </li>
              <li>
                <form action="/api/auth/signout" method="post">
                  <button type="submit" className="hover:text-indigo-200">
                    Sign Out
                  </button>
                </form>
              </li>
            </ul>
          </nav>
        </div>
      </header>
      
      <main>{children}</main>
    </div>
  );
}