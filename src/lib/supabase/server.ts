// Update src/lib/supabase/server.ts
import { createServerComponentClient } from '@supabase/auth-helpers-nextjs';
import { cookies } from 'next/headers';
import type { Database } from './types';

export const createClient = () => {
  return createServerComponentClient<Database>({ cookies });
};