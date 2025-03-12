export type Json =
  | string
  | number
  | boolean
  | null
  | { [key: string]: Json | undefined }
  | Json[]

export interface Database {
  public: {
    Tables: {
      profiles: {
        Row: {
          id: string
          email: string
          role: 'admin' | 'worker'
          created_at: string
          updated_at: string
        }
        Insert: {
          id: string
          email: string
          role: 'admin' | 'worker'
          created_at?: string
          updated_at?: string
        }
        Update: {
          id?: string
          email?: string
          role?: 'admin' | 'worker'
          created_at?: string
          updated_at?: string
        }
      }
      services: {
        Row: {
          id: string
          name: string
          default_price: number
          created_at: string
          updated_at: string
        }
        Insert: {
          id?: string
          name: string
          default_price: number
          created_at?: string
          updated_at?: string
        }
        Update: {
          id?: string
          name?: string
          default_price?: number
          created_at?: string
          updated_at?: string
        }
      }
      transactions: {
        Row: {
          id: string
          worker_id: string
          service_id: string
          customer_name: string | null
          price: number
          qr_code: string
          status: 'pending' | 'completed' | 'cancelled'
          created_at: string
          updated_at: string
        }
        Insert: {
          id?: string
          worker_id: string
          service_id: string
          customer_name?: string | null
          price: number
          qr_code: string
          status: 'pending' | 'completed' | 'cancelled'
          created_at?: string
          updated_at?: string
        }
        Update: {
          id?: string
          worker_id?: string
          service_id?: string
          customer_name?: string | null
          price?: number
          qr_code?: string
          status?: 'pending' | 'completed' | 'cancelled'
          created_at?: string
          updated_at?: string
        }
      }
    }
  }
}